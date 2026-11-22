# PANDUAN DEPLOYMENT CLOUD RESMI
## BetulinAja — Frontend Vercel & Backend Laravel Cloud

Panduan lengkap deployment dua tingkat (*decoupled architecture*):
1. **Frontend:** Di-deploy ke **Vercel** (Global Edge CDN, Static SPA/MPA)
2. **Backend API:** Di-deploy ke **Railway / Render / VPS** (PHP 8.2+ & Laravel Engine)
3. **Database:** Menggunakan **Cloud Database Persisten** (Aiven MySQL / Supabase / Neon PostgreSQL)

---

## BAGIAN 1 — DEPLOYMENT FRONTEND KE VERCEL

### Langkah 1: Persiapan Repository GitHub
Pastikan seluruh source code project telah di-commit ke branch `main` pada remote GitHub Anda:
```bash
git add .
git commit -m "feat: complete decoupled frontend for Vercel deployment"
git push origin main
```

### Langkah 2: Import Project di Vercel Dashboard
1. Buka [https://vercel.com](https://vercel.com) dan login menggunakan akun GitHub Anda.
2. Klik tombol **"Add New..."** $\rightarrow$ **"Project"**.
3. Pilih repository **BetulinAja**.
4. Pada bagian **Configure Project**:
   - **Project Name:** `betulinaja` (atau sesuai keinginan Anda)
   - **Framework Preset:** Pilih `Other`
   - **Root Directory:** Klik **Edit** $\rightarrow$ pilih folder **`frontend`** $\rightarrow$ klik **Continue**.
   > [!IMPORTANT]
   > Memilih folder `frontend` sebagai Root Directory memastikan Vercel hanya menyajikan file statis (`index.html`, `login.html`, `js/`, `assets/`, dll.) dan tidak menjalankan serverless PHP.

### Langkah 3: Konfigurasi Environment Variables di Vercel
Pada bagian **Environment Variables**, tambahkan:
| Key | Value Contoh | Deskripsi |
|---|---|---|
| `API_BASE_URL` | `https://api.betulinaja.id/api/v1` (atau URL Railway/Render Anda) | Endpoint REST API Backend Laravel |

### Langkah 4: Klik "Deploy"
1. Klik tombol **Deploy**.
2. Tunggu proses build selesai (biasanya sekitar 10 - 20 detik).
3. Anda akan mendapatkan URL produksi HTTPS, contoh: `https://betulinaja.vercel.app`.

---

## BAGIAN 2 — DEPLOYMENT BACKEND LARAVEL KE RAILWAY / RENDER

### Opsi A: Railway (Sangat Disarankan untuk Laravel)
1. Buka [https://railway.app](https://railway.app) dan login dengan GitHub.
2. Klik **New Project** $\rightarrow$ **Deploy from GitHub repo** $\rightarrow$ pilih **BetulinAja**.
3. Pada tab **Variables**, tambahkan environment variables penting:
   ```dotenv
   APP_NAME=BetulinAja
   APP_ENV=production
   APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
   APP_DEBUG=false
   APP_URL=https://your-backend.up.railway.app

   DB_CONNECTION=mysql
   DB_HOST=your-mysql-cloud.com
   DB_PORT=3306
   DB_DATABASE=betulinaja
   DB_USERNAME=your_db_user
   DB_PASSWORD=your_db_password

   # Izinkan domain Vercel Anda melakukan request API:
   CORS_ALLOWED_ORIGINS=https://betulinaja.vercel.app,http://localhost:8000
   ```
4. Tambahkan perintah deploy / build di Railway (Settings $\rightarrow$ Build Command):
   ```bash
   composer install --no-dev --optimize-autoloader && php artisan migrate --force && php artisan storage:link
   ```
5. Railway akan mendeteksi Nginx/PHP buildpack secara otomatis dan menjalankan `public/index.php`.

---

## BAGIAN 3 — CHECKLIST VERIFIKASI SETELAH DEPLOY

Lakukan pengujian berikut secara berurutan:

1. **Uji CORS:** Buka browser Console di URL Vercel Anda (`https://your-app.vercel.app`). Pastikan tidak ada pesan error `CORS Missing Allow Origin`.
2. **Uji Login Demo:**
   - Buka `/login.html`
   - Klik tab **Pelanggan** $\rightarrow$ login dengan `customer@demo.id` / `demo123456`.
   - Pastikan token tersimpan di browser dan navbar menampilkan inisial `BP` (Budi Pratama).
3. **Uji Pembuatan Pesanan (Booking):**
   - Buka `/booking.html`
   - Pilih layanan $\rightarrow$ isi deskripsi kendala $\rightarrow$ konfirmasi pemesanan.
   - Pastikan pesanan terkirim ke cloud dan diarahkan ke `/tracking.html?id=...`.
4. **Uji Portal Mitra:**
   - Logout lalu login sebagai mitra: `partner@demo.id` / `demo123456`.
   - Buka `/partner/dashboard.html` $\rightarrow$ periksa pesanan masuk $\rightarrow$ klik **Terima**.
   - Buka `/partner/active-job.html` $\rightarrow$ klik status berurutan: **Berangkat** $\rightarrow$ **Tiba** $\rightarrow$ **Kerjakan** $\rightarrow$ **Selesai**.
5. **Uji Portal Admin:**
   - Logout lalu login sebagai admin: `admin@betulinaja.id` / `demo123456`.
   - Buka `/admin/dashboard.html` $\rightarrow$ pantau grafik pesanan dan total pendapatan.
   - Buka `/admin/orders.html` $\rightarrow$ pastikan seluruh pesanan tampil dan dapat ditugaskan manual.

---

## BAGIAN 4 — MENJALANKAN LOKAL SECARA SIMULTAN

Jika ingin menjalankan frontend dan backend secara lokal di komputer pengembang:
1. **Jalankan Backend Laravel:**
   ```bash
   cd c:\xampp\htdocs\BetulinAja
   php artisan serve --port=8000
   ```
2. **Jalankan Frontend:**
   - Cukup buka `frontend/index.html` langsung di browser, atau
   - Menggunakan VS Code extension **Live Server** (klik *Go Live* pada folder `frontend`), atau
   - Menggunakan PHP built-in server:
     ```bash
     cd frontend
     php -S 127.0.0.1:5500
     ```
   Frontend akan secara otomatis mendeteksi backend lokal di `http://127.0.0.1:8000/api/v1`.
