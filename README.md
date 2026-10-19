# BetulinAja — Frontend Marketplace Jasa Service & Perbaikan Rumah

> **"Semua Masalah Rumah, BetulinAja!"**  
> Platform marketplace modern yang menghubungkan pelanggan dengan teknisi profesional untuk service AC, elektronik, listrik, plumbing, kulkas, komputer, dan perbaikan rumah lainnya secara transparan, aman, dan mudah.

### 🎓 Tim Pengembang — Universitas Insan Pembangunan Indonesia (UNIPI)
| Nama | Peran | Kontak |
|---|---|---|
| **Iqbal Adityajaya** | Pembuat & Pengembang (Lead Developer) | 085714310516 |
| **Maulana Rizki** | Penemu (Founder / Innovator) | - |
| **Sapriadi Sandy** | CEO (Chief Executive Officer) | - |

---

## 1. Ikhtisar Project

Repositori ini memuat **tahap frontend lengkap (UI/UX)** untuk platform **BetulinAja** yang dibangun di atas arsitektur **Laravel 12 Blade Templates** dengan pendekatan **Mobile-First Responsive Design**.

### Palet Warna & Identitas Visual
- **Primary Blue:** `#2563EB` (Aksi utama, tombol CTA, link aktif)
- **Dark Navy:** `#14213D` (Sidebar admin, heading hero, footer)
- **Soft Blue:** `#EFF6FF` (Latar belakang elemen terpilih, ikon, banner informasi)
- **Green Accent:** `#10B981` (Status berhasil, verifikasi teknisi, tombol terima order)
- **Soft Green:** `#ECFDF5` (Badge status sukses & verifikasi)
- **Background:** `#F8FAFC`
- **Surface:** `#FFFFFF`
- **Main Text:** `#1E293B`
- **Secondary Text:** `#64748B`
- **Border:** `#E2E8F0`

### Teknologi yang Digunakan
- **Framework:** Laravel 12 (Blade Engine)
- **CSS Framework:** Bootstrap 5.3.3 + Custom Design System (`app.css`, `customer.css`, `partner.css`, `admin.css`)
- **Typography:** Inter (Google Fonts)
- **Ikon:** Bootstrap Icons (v1.11.3)
- **Peta Interaktif:** Leaflet.js (v1.9.4) & OpenStreetMap
- **Grafik Dashboard:** Chart.js (v4.4.2)
- **JavaScript:** Vanilla JS dengan modul terstruktur (`app.js`, `customer.js`, `partner.js`, `admin.js`)

---

## 2. Cara Menjalankan Project Secara Lokal

### Prasyarat
1. **PHP:** Versi 8.2 atau lebih tinggi (disarankan via XAMPP / Laragon).
2. **Composer:** Versi 2.x terpasang di sistem.

### Langkah Instalasi
1. Masuk ke direktori project:
   ```bash
   cd c:\xampp\htdocs\BetulinAja
   ```

2. Jalankan instalasi dependensi (jika belum terpasang):
   ```bash
   composer install
   ```

3. Salin konfigurasi environment & generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. Jalankan local development server:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di: **`http://127.0.0.1:8000`** atau melalui Apache XAMPP di **`http://localhost/BetulinAja/public/`**.

---

## 3. Daftar Lengkap Halaman & Route

### A. Portal Pelanggan (Customer)
| Halaman | Route URL | Deskripsi |
|---|---|---|
| **Beranda (Homepage)** | `/` | Hero section, 8 kategori jasa, 4 keunggulan, 4 langkah cara kerja, teknisi rekomendasi, FAQ, dan CTA |
| **Katalog Layanan** | `/customer/services` | Daftar lengkap perbaikan dengan filter kategori interaktif (AC, Listrik, Plumbing, dll.) dan live search |
| **Form Pemesanan (5 Tahap)** | `/customer/booking` | Form wizard: Detail kerusakan & foto, Lokasi & Leaflet Map, Estimasi biaya, Pilih teknisi, Konfirmasi pesanan |
| **Cek Wilayah Layanan** | `/customer/location` | Peta jangkauan Leaflet dengan radius zona operasional dan pencarian kelurahan/kecamatan |
| **Kalkulator Estimasi Biaya** | `/customer/estimate` | Simulator mandiri untuk mencoba kombinasi kategori, jarak km, dan tingkat kesulitan dengan kalkulasi seketika |
| **Direktori Mitra Teknisi** | `/customer/technicians` | Daftar teknisi bersertifikat lengkap dengan filter rating, spesialisasi, dan status ketersediaan |
| **Konfirmasi Pesanan** | `/customer/confirmation` | Tampilan invoice sukses pasca pemesanan dengan detail invoice dan tombol pelacakan langsung |
| **Live Tracking Pesanan** | `/customer/tracking` | Pelacakan teknisi real-time di peta Leaflet, timeline status vertikal dengan animasi pulse, serta demo chat & telepon |
| **Riwayat Pesanan** | `/customer/order-history` | Tab filter pesanan (Semua, Aktif, Selesai, Dibatalkan) dan tombol pesan ulang cepat |
| **Profil Pengguna** | `/customer/profile` | Kelola profil, verifikasi kontak WhatsApp, dan manajemen daftar alamat tersimpan |

### B. Portal Mitra Teknisi (Partner)
| Halaman | Route URL | Deskripsi |
|---|---|---|
| **Login Mitra** | `/partner/login` | Halaman login teknisi dengan pemberitahuan mode demo one-click |
| **Dashboard Mitra** | `/partner/dashboard` | Statistik kinerja, toggle Online/Offline interaktif, alert pekerjaan aktif, dan pesanan masuk sekitar |
| **Pesanan Masuk** | `/partner/orders` | Kartu pesanan baru dengan tombol Terima (Accept) dan Tolak (Reject) disertai notifikasi toast |
| **Detail Pesanan** | `/partner/order-detail` | Rincian komprehensif kendala pelanggan, foto kerusakan, petunjuk lokasi, dan navigasi GPS |
| **Pengerjaan Aktif** | `/partner/active-job` | Alur pengerjaan lapangan bertahap (Mulai Perjalanan &rarr; Tiba &rarr; Mulai Kerja &rarr; Upload Foto Sebelum & Sesudah &rarr; Selesaikan) |
| **Dompet & Pendapatan** | `/partner/earnings` | Rekap saldo komisi (85%), riwayat transaksi harian, dan modal simulasi penarikan dana ke rekening bank |
| **Profil Mitra** | `/partner/profile` | Profil teknisi, sertifikasi keahlian, radius operasional, dan status verifikasi KTP/SKCK |

### C. Portal Administrator (Admin)
| Halaman | Route URL | Deskripsi |
|---|---|---|
| **Dashboard Utama** | `/admin/dashboard` | 4 kartu metrik utama, grafik tren pesanan Chart.js, diagram lingkaran kategori, dan log aktivitas sistem |
| **Manajemen Pesanan** | `/admin/orders` | Tabel transaksi interaktif dengan filter status, live search, pagination demo, dan modal detail finansial |
| **Kelola Mitra Teknisi** | `/admin/technicians` | Direktori mitra dengan status keaktifan, rating, dan tombol aksi verifikasi berkas mitra baru |
| **Kelola Pelanggan** | `/admin/customers` | Database pelanggan terdaftar, riwayat akumulasi pemesanan, dan total nilai belanja |
| **Layanan & Kategori** | `/admin/services` | Manajemen katalog jenis perbaikan, tarif acuan dasar, dan modal penambahan layanan baru |
| **Pengaturan Tarif** | `/admin/pricing` | Pengaturan tarif dasar per kategori, tarif perjalanan per km, surcharge kesulitan, serta live simulator & simpan demo |
| **Moderasi Ulasan** | `/admin/reviews` | Daftar feedback bintang 1–5 dari pelanggan dengan tombol moderasi tampilkan / sembunyikan |
| **Laporan & Analitik** | `/admin/reports` | Rekapitulasi metrik finansial platform (GMV, net revenue) dan form unduh laporan periodik (CSV/PDF) |
| **Pengaturan Sistem** | `/admin/settings` | Pengaturan identitas brand, jam operasional, persentase bagi hasil platform (15%), dan garansi layanan |

---

## 4. Fitur Interaktif Frontend

Seluruh halaman telah dilengkapi logika JavaScript vanilla tanpa error:
1. **Multi-Step Booking Wizard:** Validasi tiap langkah, upload multi-foto dengan preview & tombol hapus per-thumbnail, Leaflet draggable marker, tombol "Gunakan Lokasi Saat Ini", dan live cost calculator.
2. **Kalkulator Biaya Otomatis:** Menghitung `Total = Biaya Dasar + (Jarak km * Tarif/km) + Surcharge Kesulitan`.
3. **Simulasi Pelacakan & Live Map:** Peta rute Leaflet dengan ikon kendaraan teknisi, tombol *"Simulasikan Status Berikutnya"* untuk menguji seluruh siklus status order, serta modal chat interaktif dengan auto-reply teknisi.
4. **Alur Kerja Mitra (Active Job):** Tombol aksi bertahap dari meluncur ke lokasi hingga upload bukti foto sebelum & sesudah dan penyelesaian order.
5. **Chart.js Dinamis:** Grafik garis performa pesanan dan grafik donat distribusi kategori di dashboard admin.
6. **Toast Notification Engine:** Notifikasi mengambang yang responsif untuk setiap interaksi aksi.

---

## 5. Standar Responsivitas Mobile-First

Website dirancang dengan standar responsivitas menyeluruh:
- **Mobile (< 576px):** Grid kategori 2 kolom, navbar berubah menjadi hamburger menu yang bersih, bottom navigation bar pada portal mitra, offcanvas menu pada admin, tombol target sentuh minimal 44 × 44 px, dan form input 100% lebar tanpa horizontal overflow.
- **Tablet (576px – 991px):** Grid 3–4 kolom yang seimbang, tata letak kartu rapi.
- **Desktop (>= 992px):** Sidebar permanen yang elegan (putih pada mitra, dark navy pada admin), tata letak multikolom, dan dropdown portal switcher di navbar utama.

---

## 6. Catatan Integrasi Backend Selanjutnya

Project ini mematuhi instruksi khusus tahap 1:
- Tidak membuat tabel database MySQL nyata.
- Tidak membuat autentikasi berbasis session database.
- Mock data terstruktur rapi pada Blade view dan JavaScript objek sehingga siap digantikan dengan Endpoint Controller, Eloquent Model, dan REST API pada tahap implementasi backend selanjutnya.
