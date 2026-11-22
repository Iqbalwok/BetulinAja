# BetulinAja — Fullstack Marketplace Jasa Service & Perbaikan Rumah

> **"Semua Masalah Rumah, BetulinAja!"**  
> Platform marketplace modern perbaikan rumah (*home repair services*) berbasis arsitektur *decoupled fullstack*: Frontend statis teroptimasi untuk **Vercel**, REST API berbasis **Laravel 12 (Sanctum)**, dan integrasi **Database Cloud Persisten**.

### 🎓 Tim Pengembang — Universitas Insan Pembangunan Indonesia (UNIPI)
*Tugas Kelompok Mata Kuliah Komputasi Awan (Cloud Computing)*

| No | Nama Mahasiswa | Peran Utama | Tanggung Jawab Teknis |
|---|---|---|---|
| 1 | **Iqbal Adityajaya** | **Lead Developer & Software Engineer** | REST API Laravel, Database Architecture, Frontend Integration (`api.js`, `auth.js`, `booking.js`), Automated Tests |
| 2 | **Maulana Rizki** | **Founder & Business Solution Architect** | Konsep Produk, Service Pricing Rules, UI/UX Workflow & Wireframe, Validasi Kebutuhan Pengguna |
| 3 | **Sapriadi Sandy** | **Chief Executive Officer & DevOps Engineer** | Cloud Infrastructure, Vercel & Railway Deployment, Security Hardening, QA & Dokumentasi |

---

## 📑 Daftar Dokumentasi Teknis Lengkap

1. 📄 **[AUDIT_REPORT.md](file:///c:/xampp/htdocs/BetulinAja/AUDIT_REPORT.md)** — Laporan audit menyeluruh, inventarisasi kode, identifikasi celah keamanan, dan rencana refactor.
2. 🗄️ **[DATABASE_ERD.md](file:///c:/xampp/htdocs/BetulinAja/DATABASE_ERD.md)** — Entity-Relationship Diagram (ERD), skema tabel relasional, constraint foreign key, formula tarif, dan panduan migrasi.
3. 🔌 **[API_DOCUMENTATION.md](file:///c:/xampp/htdocs/BetulinAja/API_DOCUMENTATION.md)** — Dokumentasi seluruh endpoint REST API v1 (Auth, Customer, Partner, Admin).
4. 🚀 **[DEPLOYMENT_GUIDE.md](file:///c:/xampp/htdocs/BetulinAja/DEPLOYMENT_GUIDE.md)** — Panduan deployment langkah-demi-langkah GitHub $\rightarrow$ Vercel $\rightarrow$ Backend Hosting Cloud.
5. 🧪 **[TESTING_REPORT.md](file:///c:/xampp/htdocs/BetulinAja/TESTING_REPORT.md)** — Laporan hasil automated testing PHPUnit (21 test cases, 89 assertions passed 100%).

---

## 🏛️ Arsitektur Sistem & Struktur Folder

```text
BetulinAja/
├── frontend/                     # Frontend Vercel (Root Directory untuk Vercel)
│   ├── index.html                # Landing page interaktif + Hero Slideshow
│   ├── login.html                # Unified Auth: Pelanggan, Mitra & Admin
│   ├── register.html             # Registrasi Pelanggan & Mitra Teknisi
│   ├── booking.html              # Multi-step booking wizard (terhubung ke API)
│   ├── order-history.html        # Riwayat pesanan pelanggan real-time
│   ├── tracking.html             # Live tracking & stepper progress pesanan
│   ├── services.html             # Katalog layanan dengan filter dinamis
│   ├── technicians.html          # Direktori mitra teknisi terverifikasi
│   ├── estimate.html             # Kalkulator estimasi biaya interaktif
│   ├── location.html             # Peta jangkauan wilayah layanan
│   ├── profile.html              # Pengaturan profil pengguna
│   ├── partner/                  # Portal Khusus Mitra Teknisi
│   │   ├── dashboard.html        # Statistik pendapatan & toggle online/offline
│   │   ├── orders.html           # Pesanan masuk & tombol accept/reject
│   │   └── active-job.html       # Lembar kerja aktif: Menuju Lokasi -> Tiba -> Mulai -> Selesai
│   ├── admin/                    # Portal Khusus Administrator
│   │   ├── dashboard.html        # Ringkasan metrik platform & grafik analitik
│   │   └── orders.html           # Manajemen seluruh pesanan & assign mitra
│   ├── assets/                   # Aset CSS kustom, gambar, dan bootstrap
│   ├── js/                       # Klien API terpusat & Auth State Manager
│   │   ├── config.js             # Deteksi otomatis URL API (Local dev vs Cloud Vercel)
│   │   ├── api.js                # Centralized Fetch wrapper + Bearer token injection
│   │   ├── auth.js               # Session lifecycle & role-based route guard
│   │   ├── booking.js            # Logika submit booking ke API cloud
│   │   ├── customer-orders.js    # Riwayat & real-time polling tracking
│   │   ├── partner.js            # Alur kerja penerimaan order & status lifecycle
│   │   └── admin.js              # Manajemen order platform & manual assignment
│   └── vercel.json               # Konfigurasi routing & header keamanan Vercel
├── app/                          # Backend Laravel 12 Engine
│   ├── Http/Controllers/Api/V1/  # REST API Controllers (Auth, Customer, Partner, Admin)
│   ├── Models/                   # Eloquent Models (Order, User, Service, etc.)
│   ├── Services/                 # Business logic: PricingService, AssignmentService, PaymentService
│   └── Middleware/               # EnsureRole, EnsurePartnerVerified, CORS
├── routes/                       # Rute API (routes/api.php) & Web (routes/web.php)
├── database/                     # Migrations & Seeders (DemoUser, Service, PricingRule, Order)
├── tests/Feature/                # PHPUnit automated tests (21 tests, 89 assertions)
├── vercel.json                   # Root Vercel rewrite configuration
└── README.md
```

---

## ⚡ Akun Demo Siap Pakai

Gunakan kredensial berikut untuk menguji seluruh hak akses dan alur kerja:

| Peran (Role) | Email / No. WhatsApp | Password | Fitur yang Dapat Diuji |
|---|---|---|---|
| **Pelanggan (Customer)** | `customer@demo.id` *(atau 081234567890)* | `demo123456` | Booking jasa, kalkulasi tarif, pantau live tracking, batalkan pesanan, riwayat order |
| **Mitra Teknisi (Partner)** | `partner@demo.id` *(atau 081298765432)* | `demo123456` | Terima/tolak order masuk, perbarui status progress pengerjaan, cek saldo pendapatan |
| **Administrator (Admin)** | `admin@betulinaja.id` *(atau 081200000001)* | `demo123456` | Pantau metrik GMV & komisi platform, monitoring pesanan, penugasan teknisi (*force-assign*) |

---

## 🚀 Panduan Menjalankan Secara Lokal

### Prasyarat
- PHP 8.2+ dengan ekstensi `pdo_mysql`, `openssl`, `mbstring`, `fileinfo`
- MySQL Server (XAMPP / Laragon / Cloud DB)
- Composer 2.x

### 1. Setup Backend Laravel
```bash
# Masuk ke direktori project
cd c:\xampp\htdocs\BetulinAja

# Instal dependensi composer
composer install

# Siapkan file .env dan generate key
cp .env.example .env
php artisan key:generate

# Jalankan migrasi dan isi database dengan data demo
php artisan migrate --seed

# Jalankan backend API server di port 8000
php artisan serve
```
Backend API aktif di: **`http://127.0.0.1:8000/api/v1`**.

### 2. Jalankan Frontend
Buka folder `frontend/` menggunakan browser favorit Anda:
- Bisa langsung membuka `frontend/index.html`, ATAU
- Menggunakan VS Code extension **Live Server** (klik *Go Live* pada folder `frontend`), ATAU
- Menggunakan local web server:
  ```bash
  cd frontend
  php -S 127.0.0.1:5500
  ```
Frontend secara otomatis berkomunikasi dengan backend Laravel lokal di `http://127.0.0.1:8000/api/v1`.

### 3. Menjalankan Automated Tests
```bash
php artisan test
```
Seluruh 21 test suites (89 assertions) akan dieksekusi dan terverifikasi **100% lulus**.

---

## 🌐 Panduan Deployment ke Vercel (Frontend)

1. Push repository ke GitHub: `git push origin main`.
2. Di Dashboard [Vercel](https://vercel.com): Klik **New Project** $\rightarrow$ pilih repo **BetulinAja**.
3. Atur **Root Directory** ke folder: **`frontend`**.
4. Tambahkan Environment Variable:
   - `API_BASE_URL` = `https://api.domain-backend-anda.com/api/v1`
5. Klik **Deploy**. Website statis Anda langsung aktif di seluruh Edge CDN global Vercel dengan HTTPS.

---

## 🛡️ Lisensi & Hak Cipta

Project ini dikembangkan sebagai karya akademis mahasiswa **Universitas Insan Pembangunan Indonesia (UNIPI)** untuk mata kuliah Komputasi Awan.
Hak cipta dilindungi undang-undang &copy; 2026 **BetulinAja Inc.**
