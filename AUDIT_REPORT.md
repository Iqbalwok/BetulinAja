# AUDIT REPORT — BETULINAJA FULLSTACK & VERCEL READINESS

**Tanggal Audit:** 22 November 2026  
**Auditor:** Senior Fullstack & DevOps Engineer  
**Project:** BetulinAja (Marketplace Jasa Servis & Perbaikan Rumah Tangga)  
**Tujuan:** Transformasi dari prototype frontend & backend terpisah menjadi aplikasi Fullstack nyata (Frontend di Vercel, Backend Laravel REST API, Cloud Database).

---

## 1. Ringkasan Eksekutif

Aplikasi **BetulinAja** telah memiliki arsitektur backend Laravel 12 yang sangat terstruktur dan komprehensif dengan **20 automated tests (65 assertions) 100% PASS**. Skema database, model Eloquent, service layer (`PricingService`, `AssignmentService`, `PaymentService`, dll.), dan endpoint REST API v1 (`/api/v1/*`) telah siap pakai.

Namun, **lapisan antarmuka (frontend)** saat ini masih berstatus **Prototype UI Statis**. Halaman HTML yang dihasilkan belum terhubung ke REST API: form booking menyimpan data acak ke `localStorage`, tombol accept/reject order hanya memanipulasi DOM, dashboard admin dan partner menggunakan data statis, dan belum ada halaman/alur login & registrasi yang terintegrasi dengan token Sanctum.

---

## 2. Inventarisasi Komponen & Teknologi

| Komponen | Status Saat Ini | Rencana Produksi |
|---|---|---|
| **Framework Backend** | Laravel 12.0 (PHP 8.2+) | Laravel 12 REST API di Cloud Host (Railway / Render / VPS) |
| **Autentikasi** | Laravel Sanctum 4.3 (Personal Access Token) | Bearer Token REST API (Cross-Origin Vercel Ready) |
| **Database** | MySQL Lokal XAMPP (`betulinaja`) | Cloud Database Persisten (Aiven / Supabase / Neon / PlanetScale / Railway MySQL) |
| **Frontend** | HTML5 + Bootstrap 5.3 + Leaflet.js + Chart.js | Static SPA / Multi-page App di **Vercel** (`frontend/`) |
| **Struktur Folder** | File `.html` bercampur di root project Laravel | Pemisahan bersih: `frontend/` (Vercel) dan `backend/` (Laravel) |
| **Pengujian** | PHPUnit Feature Tests: 20 Passed | Tambahan pengujian integrasi API & E2E alur booking |

---

## 3. Temuan Masalah & Analisis Kerentanan

### A. Lapisan Frontend (Prioritas Tinggi - CRITICAL)
1. **Booking Wizard Tidak Menyimpan ke Database:**
   - File: `assets/js/customer.js` (fungsi `submitBooking()`).
   - Masalah: Membuat order acak `'ORD-' + Math.floor(...)` dan menyimpannya di `localStorage.setItem('active_demo_order', ...)`. Backend API `POST /api/v1/orders` sama sekali tidak dipanggil.
2. **Tidak Ada Halaman Login & Register Nyata:**
   - Tidak ada form login/register untuk customer maupun admin.
   - Halaman `partner/login` hanya berisi tombol yang memicu `window.location.href = '/partner/dashboard'`.
3. **Data Dashboard Menggunakan Hardcoded HTML:**
   - `admin-dashboard.html`, `admin-orders.html`, `partner-dashboard.html`, `partner-orders.html`, `order-history.html` menampilkan baris tabel dan kartu pesanan statis.
4. **Ketiadaan API Client Terpusat:**
   - Belum ada modul JavaScript (`api.js` / `auth.js`) untuk mengelola `API_BASE_URL`, injeksi header `Authorization: Bearer <token>`, refresh token, handling response error (401, 403, 422, 500), dan animasi loading.
5. **Katalog & Estimasi Harga di Frontend Tidak Sinkron:**
   - Kategori dan layanan di-hardcode dalam JavaScript `serviceOptions` di `customer.js`, padahal database backend memiliki tabel `services` dan `pricing_rules`.

### B. Lapisan Backend & Keamanan (Prioritas Sedang - HIGH)
1. **Konfigurasi CORS Berpotensi Konflik:**
   - File: `config/cors.php`.
   - Masalah: `'allowed_origins' => ['*']` dikombinasikan dengan `'supports_credentials' => true`. Browser modern (Fetch API) memblokir request jika credentials aktif dengan wildcard origin.
   - Solusi: Untuk arsitektur Bearer Token via header `Authorization`, `supports_credentials` dapat diatur `false` atau `allowed_origins` membaca domain Vercel dari environment variable `FRONTEND_URL`.
2. **Format Upload File di Lingkungan Serverless/Cloud:**
   - Foto kerusakan dan bukti pengerjaan saat ini disimpan di disk `public` lokal (`storage/app/public`). Untuk deployment cloud, perlu dipastikan folder storage memiliki symlink yang valid atau adapter cloud storage.
3. **Rate Limiting:**
   - Route login/register sudah dilindungi `throttle:auth` (5 request/menit), dan API endpoint dilindungi `throttle:api` (60 request/menit). Ini sudah baik dan harus dipertahankan.

### C. Lapisan Deployment & DevOps (Prioritas Tinggi - HIGH)
1. **Pemisahan Direktori untuk Vercel:**
   - Saat ini file HTML (`index.html`, `booking.html`, dll.) berada di root repositori bercampur dengan file Laravel (`artisan`, `composer.json`, `app/`).
   - Vercel memerlukan direktori root yang bersih (folder `frontend/`) lengkap dengan konfigurasi `vercel.json` untuk handling routing dan asset caching.
2. **Kredensial Git:**
   - File `.env` tidak pernah ter-commit di Git (aman). File `.env.example` telah tersedia dan perlu disesuaikan untuk deployment cloud.

---

## 4. Matriks Perbaikan Berdasarkan Prioritas

| No | Modul | File Terkait | Tindakan | Prioritas |
|---|---|---|---|---|
| 1 | **Struktur Project** | Root directory | Buat folder `frontend/` dan `backend/` bersih | P0 (Urgent) |
| 2 | **API Client & Auth State** | `frontend/js/api.js`, `frontend/js/auth.js` | Modul HTTP client terpusat (fetch, token, error, loading) | P0 (Urgent) |
| 3 | **Halaman Auth** | `frontend/login.html`, `frontend/register.html` | Form login multi-role (Customer, Mitra, Admin) & register | P0 (Urgent) |
| 4 | **Form Booking Nyata** | `frontend/booking.html`, `frontend/js/booking.js` | Ambil kategori/layanan dari API, hitung tarif real, submit ke `POST /api/v1/orders` | P0 (Urgent) |
| 5 | **Tracking & Order History** | `frontend/tracking.html`, `frontend/order-history.html` | Render pesanan real dari API `GET /api/v1/orders` & detail order | P0 (Urgent) |
| 6 | **Dashboard Partner** | `frontend/partner/*.html`, `frontend/js/partner.js` | Ambil order masuk, terima/tolak order, update status real | P1 (High) |
| 7 | **Dashboard Admin** | `frontend/admin/*.html`, `frontend/js/admin.js` | Ambil metrik real, kelola pesanan, assign mitra real | P1 (High) |
| 8 | **Vercel Config** | `frontend/vercel.json` | Konfigurasi routing rewrite & asset caching | P1 (High) |
| 9 | **Backend CORS & Config** | `backend/config/cors.php`, `backend/.env.example` | Izinkan domain Vercel via env `FRONTEND_URL` | P1 (High) |
| 10 | **Dokumentasi ERD & Deploy** | `DATABASE_ERD.md`, `DEPLOYMENT_GUIDE.md` | Panduan migrasi cloud & deploy Vercel/Railway | P2 (Medium) |

---

## 5. Rencana Eksekusi Bertahap

- **FASE 1 (Selesai):** Audit menyeluruh arsitektur, kode sumber, database, dan security.
- **FASE 2:** Restrukturisasi project menjadi `frontend/` (Vercel) dan `backend/` (Laravel API) tanpa merusak fungsionalitas lokal.
- **FASE 3:** Perbaikan frontend: implementasi `api.js`, `auth.js`, login/register, booking dinamis, order history, tracking live, dan dashboard partner & admin yang terhubung ke REST API.
- **FASE 4 - 6:** Verifikasi alur bisnis end-to-end, backend authorization, dan skema database cloud persisten.
- **FASE 7 - 10:** Konfigurasi Vercel, testing menyeluruh, dan dokumentasi tugas kuliah lengkap.
