# Dokumentasi API BetulinAja (REST API v1)

Platform Marketplace Jasa Service dan Perbaikan Rumah Tangga.

## Informasi Dasar
- **Base URL:** `http://localhost:8000/api/v1`
- **Autentikasi:** Laravel Sanctum (Bearer Token)
- **Format Header:**
  ```http
  Accept: application/json
  Content-Type: application/json
  Authorization: Bearer <TOKEN>
  ```

---

## Akun Demo
| Role | Email | Password | Keterangan |
|---|---|---|---|
| **Admin** | `admin@betulinaja.id` | `demo123456` | Kelola seluruh platform, tarif, mitra, pesanan |
| **Mitra (Teknisi)** | `partner@demo.id` | `demo123456` | Teknisi AC & Listrik terverifikasi di Jakarta Pusat |
| **Mitra (Plumbing)** | `bambang.plumbing@demo.id` | `demo123456` | Teknisi pipa & saluran terverifikasi |
| **Mitra (Pending)** | `eko.pending@demo.id` | `demo123456` | Teknisi baru menunggu verifikasi admin |
| **Customer** | `customer@demo.id` | `demo123456` | Pelanggan dengan alamat tersimpan dan riwayat pesanan |

---

## Standar Format Response
### Success Response
```json
{
  "success": true,
  "message": "Pesan keberhasilan",
  "data": { ... }
}
```

### Error Response
```json
{
  "success": false,
  "message": "Pesan kesalahan",
  "errors": { ... }
}
```

---

## Ringkasan Endpoint

### 1. Autentikasi Publik (`/api/v1/auth`)
- `POST /auth/register` - Registrasi akun (customer atau partner)
- `POST /auth/login` - Login dengan email/nomor telepon & password
- `POST /auth/logout` - Logout (Cabut token aktif)
- `GET /auth/me` - Ambil profil pengguna login
- `PUT /auth/profile` - Update profil & ganti password

### 2. Publik & Katalog Layanan (`/api/v1`)
- `GET /categories` - Daftar kategori layanan aktif
- `GET /categories/{slug}` - Detail kategori dan daftar layanannya
- `GET /services` - Daftar layanan perbaikan (filter: `category_id`, `category_slug`, `search`)
- `GET /services/{slug}` - Detail layanan dan aturan tarif
- `POST /estimates` - Hitung kalkulasi estimasi harga transparan (berdasarkan jarak, tingkat kesulitan)
- `GET /technicians` - Daftar teknisi terverifikasi (filter: rating, kategori, jarak)
- `GET /technicians/{id}` - Detail profil publik teknisi beserta review

### 3. Pelanggan (`/api/v1`) — Memerlukan Token Customer
- `GET /orders` - Riwayat dan daftar pesanan pelanggan (filter: `status`)
- `POST /orders` - Buat pesanan baru (unggah foto kerusakan, estimasi tarif server-side, auto/manual assign)
- `GET /orders/{id}` - Detail pesanan (status, foto, teknisi, timeline riwayat, pembayaran)
- `POST /orders/{id}/cancel` - Batalkan pesanan sebelum teknisi tiba
- `POST /orders/{id}/photos` - Unggah foto kerusakan tambahan
- `POST /reviews` - Beri rating (1-5) dan ulasan untuk pesanan selesai
- `GET /addresses` - Daftar alamat tersimpan
- `POST /addresses` - Tambah alamat baru
- `GET /addresses/{id}` - Detail alamat
- `PUT /addresses/{id}` - Update alamat
- `DELETE /addresses/{id}` - Hapus alamat

### 4. Mitra / Teknisi (`/api/v1/partner`) — Memerlukan Token Partner
- `GET /partner/dashboard` - Statistik pekerjaan, total pendapatan hari ini, saldo, rating
- `GET /partner/orders/available` - Pesanan status `searching_technician` yang sesuai keahlian & radius
- `GET /partner/orders` - Daftar seluruh pekerjaan yang ditugaskan kepada mitra
- `GET /partner/orders/{id}` - Detail pekerjaan
- `POST /partner/orders/{id}/accept` - Terima pesanan yang ditugaskan
- `POST /partner/orders/{id}/reject` - Tolak pesanan yang ditugaskan
- `PATCH /partner/orders/{id}/status` - Update lifecycle pekerjaan:
  - `on_the_way` (Teknisi menuju lokasi)
  - `arrived` (Teknisi tiba di lokasi)
  - `in_progress` (Teknisi mulai pengerjaan)
  - `awaiting_customer_confirmation` (Pekerjaan selesai, menunggu konfirmasi pelanggan)
  - `completed` (Pekerjaan selesai, pencairan saldo otomatis ke dompet teknisi)
- `GET /partner/earnings` - Rincian saldo dan mutasi penghasilan
- `GET /partner/profile` - Profil teknisi dan keahlian
- `POST /partner/profile` - Perbarui profil teknisi dan unggah berkas KTP/sertifikat
- `POST /partner/status` - Ubah status online/offline/busy dan perbarui titik koordinat GPS

### 5. Admin (`/api/v1/admin`) — Memerlukan Token Admin
- `GET /admin/dashboard` - Ringkasan metrik platform, total transaksi, komisi platform, pesanan aktif
- `GET /admin/users` - Kelola seluruh pengguna (customer & mitra)
- `GET /admin/users/{id}` - Detail pengguna
- `PATCH /admin/users/{id}/status` - Ubah status pengguna (`active`, `inactive`, `suspended`)
- `GET /admin/partners/verification` - Daftar mitra yang menunggu verifikasi berkas
- `POST /admin/partners/{id}/verify` - Setujui verifikasi mitra
- `POST /admin/partners/{id}/reject` - Tolak verifikasi mitra beserta catatan alasan
- `GET /admin/orders` - Monitoring semua pesanan platform
- `GET /admin/orders/{id}` - Detail pesanan lengkap
- `POST /admin/orders/{id}/assign` - Penugasan paksa mitra ke pesanan
- `POST /admin/orders/{id}/cancel` - Batalkan pesanan secara administratif
- `POST /admin/categories` - Tambah kategori layanan baru
- `PUT /admin/categories/{id}` - Ubah kategori layanan
- `POST /admin/services` - Tambah jenis layanan baru
- `PUT /admin/services/{id}` - Ubah jenis layanan
- `DELETE /admin/services/{id}` - Hapus jenis layanan
- `GET /admin/pricing-rules` - Daftar aturan tarif biaya per KM & tingkat kesulitan
- `POST /admin/pricing-rules` - Tambah aturan tarif
- `PUT /admin/pricing-rules/{id}` - Ubah aturan tarif
- `DELETE /admin/pricing-rules/{id}` - Hapus aturan tarif
- `GET /admin/reviews` - Pantau dan moderasi ulasan pelanggan
- `PATCH /admin/reviews/{id}/visibility` - Sembunyikan atau tampilkan ulasan
- `DELETE /admin/reviews/{id}` - Hapus ulasan yang melanggar
- `GET /admin/reports/transactions` - Laporan transaksi keuangan berdasarkan rentang tanggal

### 6. Notifikasi (`/api/v1/notifications`) — Semua Role
- `GET /notifications` - Daftar notifikasi in-app
- `PATCH /notifications/{id}/read` - Tandai notifikasi sebagai dibaca
- `POST /notifications/read-all` - Tandai semua notifikasi sebagai dibaca

---

## Logika Bisnis Utama
1. **Perhitungan Tarif Server-Side (Otoritatif):**
   - Biaya dasar layanan: Mengambil dari tarif layanan.
   - Biaya transportasi: Menggunakan formula Haversine dengan faktor jalan (1.3x) dikalikan tarif per kilometer.
   - Biaya tingkat kesulitan: Ringan (Rp 0), Sedang (Rp 25.000), Berat (Rp 60.000).
   - Snapshot tarif disimpan permanen pada saat pembuatan order, tidak berubah jika tarif global berubah di kemudian hari.
2. **Penugasan Teknisi Bebas Race Condition:**
   - Menggunakan transaksi database dengan *pessimistic locking* (`lockForUpdate`) untuk memastikan teknisi tidak ditugaskan ganda.
   - Algoritma pencocokan mempertimbangkan kesesuaian kategori keahlian, status online, verifikasi admin, jarak radius terdekat, dan rating rata-rata.
3. **Penyelesaian & Pembayaran:**
   - Setelah order berstatus `completed`, sistem memotong komisi platform (15%) dan secara otomatis menambahkan porsi mitra (85%) ke saldo dompet mitra (`partnerProfile.balance`).
