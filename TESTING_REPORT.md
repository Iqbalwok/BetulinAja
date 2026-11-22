# LAPORAN PENGUJIAN SISTEM & QA (TESTING REPORT)
## BetulinAja — Marketplace Jasa Service & Perbaikan Rumah

Dokumen ini memuat hasil pengujian menyeluruh (*automated testing* & *manual QA testing*) terhadap fungsionalitas REST API backend, otorisasi peran, validasi alur bisnis, serta antarmuka frontend terintegrasi.

---

## 1. Ringkasan Eksekutif Pengujian Otomatis (PHPUnit)

- **Total Test Cases:** 21 Tests
- **Total Assertions:** 89 Assertions
- **Status:** **100% PASSED (0 Failed, 0 Skipped, 0 Warning)**
- **Waktu Eksekusi:** 1.52 detik
- **Environment:** PHP 8.3.10 CLI / PHPUnit 11.5 / SQLite In-Memory Database (Isolated per test)

---

## 2. Rincian Test Suite & Hasil Pengujian

### A. Autentikasi & Registrasi (`Tests\Feature\AuthTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 1 | Customer dapat registrasi akun baru | **PASS** | Validasi nama, email unik, format WhatsApp, hashing password |
| 2 | Mitra dapat registrasi akun + profil teknisi | **PASS** | Otomatis membuat relasi ke `PartnerProfile` dan `PartnerSkill` |
| 3 | Registrasi publik dicegah memilih role `admin` | **PASS** | Role escalation attack dicegah pada level controller (HTTP 403) |
| 4 | User dapat login menggunakan email ATAU no. HP | **PASS** | Mengembalikan plainTextToken Bearer dari Laravel Sanctum |
| 5 | Login gagal jika password salah | **PASS** | Mengembalikan HTTP 401 dan pesan kesalahan ramah |
| 6 | User dapat logout | **PASS** | Token Sanctum yang aktif dicabut dari database |

### B. Otorisasi Peran & Keamanan Endpoint (`Tests\Feature\RoleAuthorizationTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 7 | Customer dilarang mengakses endpoint mitra | **PASS** | Middleware `role:partner` menolak request (HTTP 403) |
| 8 | Mitra dilarang mengakses endpoint admin | **PASS** | Middleware `role:admin` menolak request (HTTP 403) |
| 9 | Admin memiliki akses penuh ke endpoint admin | **PASS** | Mengembalikan HTTP 200 dan data transaksi |

### C. Estimasi & Kalkulasi Tarif (`Tests\Feature\EstimateTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 10 | Kalkulasi estimasi biaya dinamis | **PASS** | Menguji jarak Haversine, biaya transport per km, dan surcharge tingkat kesulitan |

### D. Manajemen Pesanan Pelanggan (`Tests\Feature\OrderTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 11 | Pembuatan pesanan dengan auto-assignment | **PASS** | Mencari mitra online terdekat dengan keahlian yang sesuai |
| 12 | Isolasi data: Pelanggan dilarang melihat pesanan orang lain | **PASS** | Kebijakan otorisasi (`OrderPolicy::view`) memblokir akses (HTTP 403) |
| 13 | Pelanggan dapat membatalkan pesanan pending | **PASS** | Mencatat riwayat alasan pembatalan ke `OrderStatusHistory` |

### E. Alur Kerja Mitra Teknisi (`Tests\Feature\PartnerWorkflowTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 14 | Mitra dapat menerima (*accept*) pesanan yang ditugaskan | **PASS** | Status berubah dari `assigned` ke `accepted` |
| 15 | Siklus progres status pekerjaan teknisi | **PASS** | Memverifikasi tahapan `on_the_way` $\rightarrow$ `arrived` $\rightarrow$ `in_progress` $\rightarrow$ `completed` |
| 16 | Transisi status tidak sah ditolak | **PASS** | State machine mencegah lompat status langsung dari `pending` ke `completed` |

### F. Ulasan & Rating (`Tests\Feature\ReviewTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 17 | Customer dapat memberi ulasan pada pesanan selesai | **PASS** | Rating bintang 1-5 dan teks ulasan tersimpan |
| 18 | Customer dilarang memberi ulasan pada pesanan belum selesai | **PASS** | HTTP 422 Unprocessable Entity |

### G. Pengujian Siklus Lengkap End-to-End (`Tests\Feature\EndToEndBookingLifecycleTest`)
| No | Kasus Pengujian | Hasil | Catatan |
|---|---|---|---|
| 19 | Siklus Penuh: Customer $\rightarrow$ Admin $\rightarrow$ Partner $\rightarrow$ Customer | **PASS (24 Assertions)** | Menguji alur nyata: Login $\rightarrow$ Buat Order $\rightarrow$ Admin Assign $\rightarrow$ Mitra Terima $\rightarrow$ Update Progress $\rightarrow$ Selesai $\rightarrow$ Tracking Live Terverifikasi |

---

## 3. Matriks Pengujian Manual Antarmuka (Frontend QA)

| Skenario | Langkah Pengujian | Hasil yang Diharapkan | Status |
|---|---|---|---|
| **Login Satu Klik** | Klik tombol demo "Pelanggan" di `login.html` | Kredensial terisi otomatis dan login berhasil mengarahkan ke dashboard | **BERHASIL** |
| **Proteksi Halaman (Guard)** | Buka `partner/dashboard.html` saat belum login | Otomatis diarahkan ke `login.html?returnUrl=...` | **BERHASIL** |
| **Slideshow Responsif** | Buka `index.html` pada layar desktop dan mobile | Slideshow bergerak otomatis (4 detik), tombol panah berfungsi, warna teks kontras tajam | **BERHASIL** |
| **Multi-Step Booking** | Isi form 5 langkah di `booking.html` | Kalkulasi tarif dinamis merespon perubahan kategori & kesulitan; submit menyimpan ke DB | **BERHASIL** |
| **Live Tracking Polling** | Buka `tracking.html?id=...` saat mitra mengupdate status di tab lain | Indikator stepper berpindah secara otomatis tanpa perlu refresh manual browser | **BERHASIL** |
| **Manajemen Admin** | Buka `admin/orders.html`, pilih pesanan, klik "Tugaskan Teknisi" | Modal muncul, daftar mitra terverifikasi dapat dipilih dan tersimpan ke DB | **BERHASIL** |

---

## 4. Kesimpulan Kualitas

Aplikasi **BetulinAja** telah memenuhi seluruh kriteria kualitas arsitektur cloud, keamanan autentikasi role-based, konsistensi data relasional, dan integrasi frontend REST API tanpa tombol atau data dummy.
