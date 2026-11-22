# DATABASE ARCHITECTURE & ENTITY-RELATIONSHIP DIAGRAM (ERD)
## BetulinAja — Marketplace Jasa Service & Perbaikan Rumah

Dokumen ini memuat spesifikasi arsitektur basis data, Entity-Relationship Diagram (ERD), skema tabel, indeks relasional, serta panduan migrasi basis data cloud persisten.

---

## 1. Visual Entity-Relationship Diagram (Mermaid)

```mermaid
erDiagram
    USERS ||--o| PARTNER_PROFILES : "has profile"
    USERS ||--o{ ADDRESSES : "owns addresses"
    USERS ||--o{ ORDERS : "places as customer"
    USERS ||--o{ ORDERS : "handled as partner"
    USERS ||--o{ REVIEWS : "writes"
    USERS ||--o{ NOTIFICATIONS : "receives"
    
    SERVICE_CATEGORIES ||--o{ SERVICES : "categorizes"
    SERVICE_CATEGORIES ||--o{ PARTNER_SKILLS : "categorized by"
    
    PARTNER_PROFILES ||--o{ PARTNER_SKILLS : "possesses"
    
    SERVICES ||--o{ ORDERS : "ordered in"
    SERVICES ||--o| PRICING_RULES : "governed by"
    
    ORDERS ||--o{ ORDER_STATUS_HISTORIES : "tracks status changes"
    ORDERS ||--o{ ORDER_PHOTOS : "contains evidence"
    ORDERS ||--o| PAYMENTS : "billed through"
    ORDERS ||--o| REVIEWS : "evaluated by"

    USERS {
        bigint id PK
        string name
        string email UK
        string phone UK
        timestamp email_verified_at
        string password
        enum role "customer | partner | admin"
        enum status "active | inactive | suspended"
        string remember_token
        timestamps created_at_updated_at
    }

    PARTNER_PROFILES {
        bigint id PK
        bigint user_id FK
        text description
        string id_card_number
        string id_card_photo
        string certificate_photo
        enum verification_status "pending | verified | rejected"
        text verification_note
        enum availability_status "online | offline | busy"
        decimal current_latitude
        decimal current_longitude
        decimal average_rating
        int total_reviews
        int completed_jobs
        int years_experience
        decimal balance
        timestamps created_at_updated_at
    }

    PARTNER_SKILLS {
        bigint id PK
        bigint partner_id FK
        bigint category_id FK
        int experience_years
        timestamps created_at_updated_at
    }

    SERVICE_CATEGORIES {
        bigint id PK
        string name
        string slug UK
        string icon
        string description
        boolean is_active
        timestamps created_at_updated_at
    }

    SERVICES {
        bigint id PK
        bigint category_id FK
        string name
        string slug UK
        text description
        decimal base_price
        int duration_minutes
        boolean is_active
        timestamps created_at_updated_at
    }

    PRICING_RULES {
        bigint id PK
        bigint service_id FK
        bigint category_id FK
        decimal base_price
        decimal travel_fee_per_km
        decimal difficulty_fee_light
        decimal difficulty_fee_medium
        decimal difficulty_fee_heavy
        decimal platform_commission_rate
        timestamps created_at_updated_at
    }

    ADDRESSES {
        bigint id PK
        bigint user_id FK
        string label
        text address
        string landmark
        string city
        string district
        decimal latitude
        decimal longitude
        boolean is_primary
        timestamps created_at_updated_at
    }

    ORDERS {
        bigint id PK
        string order_number UK
        bigint customer_id FK
        bigint partner_id FK
        bigint service_id FK
        bigint address_id FK
        text problem_description
        text address_text
        string landmark
        decimal latitude
        decimal longitude
        timestamp scheduled_at
        enum assignment_method "auto | manual"
        enum status "pending | searching_technician | assigned | accepted | on_the_way | arrived | in_progress | awaiting_customer_confirmation | completed | cancelled"
        decimal base_price
        decimal distance_km
        decimal travel_fee
        enum difficulty_level "light | medium | heavy"
        decimal difficulty_fee
        decimal additional_fee
        decimal total_estimate
        decimal final_price
        decimal platform_commission
        decimal partner_earning
        text cancellation_reason
        bigint cancelled_by FK
        timestamp accepted_at
        timestamp started_at
        timestamp completed_at
        timestamp deleted_at "soft delete"
        timestamps created_at_updated_at
    }

    ORDER_STATUS_HISTORIES {
        bigint id PK
        bigint order_id FK
        bigint changed_by FK
        string old_status
        string new_status
        text note
        timestamps created_at_updated_at
    }

    ORDER_PHOTOS {
        bigint id PK
        bigint order_id FK
        string file_path
        string original_name
        enum photo_type "damage | before_work | after_work"
        bigint file_size
        timestamps created_at_updated_at
    }

    PAYMENTS {
        bigint id PK
        bigint order_id FK
        string payment_code UK
        enum method "cash | transfer | qris"
        enum status "pending | paid | failed | refunded"
        decimal amount
        timestamp paid_at
        timestamps created_at_updated_at
    }

    REVIEWS {
        bigint id PK
        bigint order_id FK
        bigint customer_id FK
        bigint partner_id FK
        int rating
        text review_text
        boolean is_visible
        timestamps created_at_updated_at
    }

    NOTIFICATIONS {
        bigint id PK
        bigint user_id FK
        string title
        text message
        string type
        json data
        boolean is_read
        timestamps created_at_updated_at
    }
```

---

## 2. Aturan Bisnis & Rumus Kalkulasi

### Formula Penentuan Tarif Total
Perhitungan estimasi biaya perbaikan menggunakan formula berjenjang:
$$\text{Total Estimate} = \text{Base Price} + (\text{Distance Km} \times \text{Travel Fee per Km}) + \text{Difficulty Surcharge} + \text{Additional Fee}$$

- **Travel Fee per Km:** Default Rp5.000 / km berdasarkan kalkulasi jarak Haversine bola bumi.
- **Difficulty Surcharge:**
  - `light` (Ringan): Rp0
  - `medium` (Sedang): Rp25.000
  - `heavy` (Berat): Rp60.000
- **Bagi Hasil Finansial:**
  - Komisi Platform (`platform_commission`): 15% dari `final_price`
  - Hak Pendapatan Mitra (`partner_earning`): 85% dari `final_price`

### Siklus Status Pesanan (Order Lifecycle State Machine)
Setiap pesanan dijamin mengikuti transisi yang valid melalui `Order::VALID_TRANSITIONS`:
1. `pending` $\rightarrow$ `searching_technician` atau `cancelled`
2. `searching_technician` $\rightarrow$ `assigned` atau `cancelled`
3. `assigned` $\rightarrow$ `accepted`, `searching_technician` (bila ditolak mitra), atau `cancelled`
4. `accepted` $\rightarrow$ `on_the_way` atau `cancelled`
5. `on_the_way` $\rightarrow$ `arrived`
6. `arrived` $\rightarrow$ `in_progress`
7. `in_progress` $\rightarrow$ `awaiting_customer_confirmation` atau `completed`
8. `completed` (Selesai, saldo mitra dikreditkan, ulasan aktif)
9. `cancelled` (Dibatalkan)

---

## 3. Panduan Menjalankan Migrasi & Database Cloud

### Opsi 1: Cloud MySQL (Aiven / PlanetScale / Railway MySQL / Clever Cloud)
1. Buat database MySQL di penyedia cloud.
2. Tambahkan kredensial ke `.env`:
   ```dotenv
   DB_CONNECTION=mysql
   DB_HOST=your-mysql-cloud-host.com
   DB_PORT=3306
   DB_DATABASE=betulinaja
   DB_USERNAME=your_username
   DB_PASSWORD=your_secure_password
   ```

### Opsi 2: Cloud PostgreSQL (Supabase / Neon.tech / Railway PostgreSQL)
1. Salin connection string dari dashboard Supabase atau Neon.
2. Tambahkan kredensial ke `.env`:
   ```dotenv
   DB_CONNECTION=pgsql
   DB_HOST=ep-example.neon.tech
   DB_PORT=5432
   DB_DATABASE=neondb
   DB_USERNAME=neondb_owner
   DB_PASSWORD=your_neon_password
   ```

### Menjalankan Migration & Seeding
```bash
# Jalankan migrasi seluruh tabel
php artisan migrate

# Isi data awal demo pengguna, kategori, layanan, dan contoh transaksi
php artisan db:seed

# Cek status tabel
php artisan db:show
```
