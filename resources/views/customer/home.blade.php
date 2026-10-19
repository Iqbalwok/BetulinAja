@extends('layouts.customer')

@section('title', 'BetulinAja — Solusi Service & Perbaikan Rumah Terpercaya')

@section('customer_content')
<!-- =========================================================================
     HERO SLIDESHOW CAROUSEL (PALING ATAS, AUTO-SLIDE & INTERAKTIF)
     ========================================================================= -->
<section class="pt-3 pt-md-4 pb-4 bg-light border-bottom">
  <div class="container">
    <div id="promoCarousel" class="carousel slide carousel-fade hero-carousel-container shadow" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover" style="border-radius: 20px; overflow: hidden;">
      <!-- Indicators (Pills) -->
      <div class="carousel-indicators carousel-indicators-pill">
        <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1: Solusi Perbaikan Rumah"></button>
        <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="1" aria-label="Slide 2: Promo Cuci AC"></button>
        <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="2" aria-label="Slide 3: Inovasi Mahasiswa UNIPI"></button>
      </div>

      <!-- Carousel Inner -->
      <div class="carousel-inner">
        <!-- Slide 1: Solusi Utama (Brand Hero) -->
        <div class="carousel-item active hero-carousel-item" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=1400&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
          <!-- High Contrast Dark Gradient Overlay agar font tidak nabrak -->
          <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(90deg, rgba(15, 23, 42, 0.96) 0%, rgba(15, 23, 42, 0.88) 55%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
          
          <div class="container position-relative py-4 px-4 px-md-5" style="z-index: 2; max-width: 680px; margin-left: 0;">
            <div class="d-inline-flex align-items-center gap-2 bg-success text-white px-3 py-1 rounded-pill small fw-bold mb-3 shadow-sm">
              <i class="bi bi-shield-fill-check"></i>
              <span>100% Teknisi Terverifikasi & Bergaransi 30 Hari</span>
            </div>
            
            <h1 class="display-6 fw-bolder text-white mb-3" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6); line-height: 1.2;">
              Semua Masalah Rumah, <br><span class="text-primary" style="color: #60A5FA !important;">BetulinAja!</span>
            </h1>
            
            <p class="mb-4 text-light lead small fs-6" style="color: #E2E8F0 !important; max-width: 580px; text-shadow: 0 1px 4px rgba(0,0,0,0.5); line-height: 1.6;">
              Panggil teknisi profesional terdekat untuk service AC, instalasi listrik, plumbing pipa mampet, dan perbaikan elektronik. Cepat, transparan, dan berstandar SOP resmi.
            </p>
            
            <div class="d-flex flex-wrap align-items-center gap-3">
              <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom btn-lg px-4 shadow">
                <i class="bi bi-calendar-check me-2"></i> Pesan Sekarang
              </a>
              <a href="{{ url('/customer/estimate') }}" class="btn btn-light btn-lg px-4 fw-semibold text-dark shadow-sm">
                <i class="bi bi-calculator me-2 text-primary"></i> Cek Estimasi Biaya
              </a>
            </div>
          </div>
        </div>

        <!-- Slide 2: Promo Cuci & Service AC -->
        <div class="carousel-item hero-carousel-item" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=1400&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
          <!-- High Contrast Dark Gradient Overlay -->
          <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(90deg, rgba(15, 23, 42, 0.96) 0%, rgba(15, 23, 42, 0.88) 55%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
          
          <div class="container position-relative py-4 px-4 px-md-5" style="z-index: 2; max-width: 680px; margin-left: 0;">
            <div class="d-inline-flex align-items-center gap-2 bg-warning text-dark px-3 py-1 rounded-pill small fw-bold mb-3 shadow-sm">
              <i class="bi bi-tag-fill text-dark"></i>
              <span>Promo Spesial &bull; Mulai Rp75.000</span>
            </div>
            
            <h1 class="display-6 fw-bolder text-white mb-3" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6); line-height: 1.2;">
              Cuci AC Bersih Tuntas, Udara Sejuk Bebas Bakteri
            </h1>
            
            <p class="mb-4 text-light lead small fs-6" style="color: #E2E8F0 !important; max-width: 580px; text-shadow: 0 1px 4px rgba(0,0,0,0.5); line-height: 1.6;">
              Pembersihan menyeluruh unit indoor & outdoor, pengecekan tekanan freon, dan perbaikan kebocoran air. Teknisi tiba dalam 30 menit.
            </p>
            
            <div class="d-flex flex-wrap align-items-center gap-3">
              <a href="{{ url('/customer/booking?cat=Service%20AC') }}" class="btn btn-primary-custom btn-lg px-4 shadow">
                <i class="bi bi-tools me-2"></i> Pesan Cuci AC
              </a>
              <a href="{{ url('/customer/services') }}" class="btn btn-light btn-lg px-4 fw-semibold text-dark shadow-sm">
                Lihat Daftar Layanan
              </a>
            </div>
          </div>
        </div>

        <!-- Slide 3: Kampus UNIPI & Tim Pengembang -->
        <div class="carousel-item hero-carousel-item" style="height: 450px; background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=1400&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
          <!-- High Contrast Dark Gradient Overlay -->
          <div style="position: absolute; top:0; left:0; right:0; bottom:0; background: linear-gradient(90deg, rgba(15, 23, 42, 0.96) 0%, rgba(15, 23, 42, 0.88) 55%, rgba(15, 23, 42, 0.3) 100%); z-index: 1;"></div>
          
          <div class="container position-relative py-4 px-4 px-md-5" style="z-index: 2; max-width: 680px; margin-left: 0;">
            <div class="d-inline-flex align-items-center gap-2 bg-primary text-white px-3 py-1 rounded-pill small fw-bold mb-3 shadow-sm">
              <i class="bi bi-mortarboard-fill"></i>
              <span>Universitas Insan Pembangunan Indonesia (UNIPI)</span>
            </div>
            
            <h1 class="display-6 fw-bolder text-white mb-3" style="text-shadow: 0 2px 8px rgba(0,0,0,0.6); line-height: 1.2;">
              Platform Inovasi Marketplace Jasa Karya Mahasiswa
            </h1>
            
            <p class="mb-4 text-light lead small fs-6" style="color: #E2E8F0 !important; max-width: 580px; text-shadow: 0 1px 4px rgba(0,0,0,0.5); line-height: 1.6;">
              Dikembangkan oleh Iqbal Adityajaya (Lead Developer), Maulana Rizki (Penemu), dan Sapriadi Sandy (CEO) untuk mendigitalisasi layanan perbaikan rumah di Indonesia.
            </p>
            
            <div class="d-flex flex-wrap align-items-center gap-3">
              <a href="#tim-pengembang" class="btn btn-primary-custom btn-lg px-4 shadow">
                <i class="bi bi-people me-2"></i> Profil Tim Pengembang
              </a>
              <a href="https://wa.me/6285714310516" target="_blank" class="btn btn-light btn-lg px-4 fw-semibold text-dark shadow-sm">
                <i class="bi bi-whatsapp text-success me-2"></i> Hubungi Pengembang
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Controls (Tombol Panah Kiri & Kanan) -->
      <button class="carousel-control-prev carousel-btn-control" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev" aria-label="Slide Sebelumnya">
        <i class="bi bi-chevron-left"></i>
      </button>
      <button class="carousel-control-next carousel-btn-control" type="button" data-bs-target="#promoCarousel" data-bs-slide="next" aria-label="Slide Berikutnya">
        <i class="bi bi-chevron-right"></i>
      </button>
    </div>

    <!-- Quick Search Bar & Trust Stats (Di Bawah Slideshow) -->
    <div class="card border-0 shadow-sm rounded-4 p-3 p-md-4 mt-3 bg-white">
      <div class="row align-items-center g-3">
        <!-- Search Input -->
        <div class="col-lg-6">
          <form action="{{ url('/customer/services') }}" method="GET" class="position-relative m-0">
            <div class="input-group">
              <span class="input-group-text bg-light border-end-0 ps-3">
                <i class="bi bi-search text-primary"></i>
              </span>
              <input type="text" name="q" class="form-control bg-light border-start-0 py-2 ps-2" placeholder="Cari layanan: Cuci AC, Kulkas mati, Pipa bocor, Listrik...">
              <button type="submit" class="btn btn-primary-custom px-4">
                Cari Jasa
              </button>
            </div>
          </form>
        </div>

        <!-- 3 Quick Metrics -->
        <div class="col-lg-6">
          <div class="d-flex align-items-center justify-content-around text-center pt-2 pt-lg-0 border-top border-lg-0">
            <div>
              <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">15.000+</h5>
              <span class="text-secondary small" style="font-size: 0.75rem;">Perbaikan Sukses</span>
            </div>
            <div class="border-start ps-3">
              <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">850+</h5>
              <span class="text-secondary small" style="font-size: 0.75rem;">Teknisi Ahli</span>
            </div>
            <div class="border-start ps-3">
              <h5 class="fw-bold text-dark mb-0 fs-6 fs-md-5">4.9 / 5.0</h5>
              <span class="text-secondary small" style="font-size: 0.75rem;">Kepuasan Pelanggan</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Kategori Layanan Grid (8 Kategori) -->
<section class="section-padding bg-white">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-between gap-3 mb-5">
      <div>
        <span class="badge-soft-primary small mb-2 d-inline-block">Kategori Pilihan</span>
        <h2 class="fw-bold text-dark mb-1">Layanan Perbaikan Rumah Kami</h2>
        <p class="text-secondary mb-0">Pilih kategori perbaikan sesuai kendala yang sedang Anda hadapi.</p>
      </div>
      <a href="{{ url('/customer/services') }}" class="btn btn-outline-custom text-nowrap">
        Lihat Semua Layanan <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-3 g-md-4">
      <!-- 1. Service AC -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Service%20AC') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-snow"></i>
          </div>
          <span class="category-name">Service AC</span>
          <span class="category-desc">Cuci, Freon & Bongkar Pasang</span>
        </a>
      </div>

      <!-- 2. Elektronik -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Elektronik') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-tv"></i>
          </div>
          <span class="category-name">Elektronik</span>
          <span class="category-desc">TV LED, Mesin Cuci, Microwave</span>
        </a>
      </div>

      <!-- 3. Listrik -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Listrik') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-lightning-charge"></i>
          </div>
          <span class="category-name">Listrik</span>
          <span class="category-desc">Korsleting, Saklar & Panel MCB</span>
        </a>
      </div>

      <!-- 4. Plumbing -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Plumbing') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-droplet"></i>
          </div>
          <span class="category-name">Plumbing</span>
          <span class="category-desc">Pipa Mampet, Kran & Pompa Air</span>
        </a>
      </div>

      <!-- 5. Perabot Rumah -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Perabot%20Rumah') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-hammer"></i>
          </div>
          <span class="category-name">Perabot Rumah</span>
          <span class="category-desc">Engsel Pintu, Lemari & Furnitur</span>
        </a>
      </div>

      <!-- 6. Komputer -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Komputer') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-laptop"></i>
          </div>
          <span class="category-name">Komputer & PC</span>
          <span class="category-desc">Instalasi, Hardware & Laptop</span>
        </a>
      </div>

      <!-- 7. Kulkas -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Kulkas') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-box-seam"></i>
          </div>
          <span class="category-name">Kulkas & Freezer</span>
          <span class="category-desc">Tidak Dingin & Kompresor</span>
        </a>
      </div>

      <!-- 8. Lainnya -->
      <div class="col-6 col-md-4 col-lg-3">
        <a href="{{ url('/customer/booking?cat=Lainnya') }}" class="category-card">
          <div class="category-icon-box">
            <i class="bi bi-grid-3x3-gap"></i>
          </div>
          <span class="category-name">Lainnya</span>
          <span class="category-desc">Pengecatan, Atap & Renovasi</span>
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Mengapa BetulinAja? (4 Keunggulan) -->
<section class="section-padding bg-light">
  <div class="container">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="badge-soft-primary small mb-2 d-inline-block">Keunggulan Layanan</span>
      <h2 class="fw-bold text-dark mb-2">Mengapa Memilih BetulinAja?</h2>
      <p class="text-secondary">Kami merevolusi cara Anda merawat rumah dengan kenyamanan dan keamanan standar tinggi.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-4 border-0">
          <div class="category-icon-box mb-3 bg-primary text-white">
            <i class="bi bi-shield-check"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Teknisi Terpercaya</h5>
          <p class="text-secondary small mb-0">Seluruh mitra melewati verifikasi identitas (KTP & SKCK), uji keahlian praktik, dan penilaian rating berkala.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-4 border-0">
          <div class="category-icon-box mb-3 bg-success text-white">
            <i class="bi bi-phone"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Proses Mudah</h5>
          <p class="text-secondary small mb-0">Cukup 3 menit untuk menjelaskan kerusakan, unggah foto, dan teknisi langsung berangkat ke lokasi Anda.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-4 border-0">
          <div class="category-icon-box mb-3 bg-warning text-dark">
            <i class="bi bi-cash-stack"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Harga Transparan</h5>
          <p class="text-secondary small mb-0">Biaya dasar dan estimasi ongkos perjalanan dihitung terbuka tanpa ada biaya tersembunyi atau mark-up sepihak.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom h-100 p-4 border-0">
          <div class="category-icon-box mb-3 bg-info text-white">
            <i class="bi bi-clock-history"></i>
          </div>
          <h5 class="fw-bold text-dark mb-2">Layanan Praktis</h5>
          <p class="text-secondary small mb-0">Bisa pesan untuk kedatangan instan hari ini atau dijadwalkan sesuai waktu luang Anda di akhir pekan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Cara Kerja (4 Langkah) -->
<section class="section-padding bg-white" id="cara-kerja">
  <div class="container">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="badge-soft-primary small mb-2 d-inline-block">Alur Pesan</span>
      <h2 class="fw-bold text-dark mb-2">Cara Kerja BetulinAja</h2>
      <p class="text-secondary">Empat langkah praktis menyelesaikan setiap kendala perbaikan rumah Anda.</p>
    </div>

    <div class="row g-4 position-relative">
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom p-4 h-100 border-0 bg-light">
          <div class="step-number-badge">1</div>
          <h5 class="fw-bold text-dark mb-2">Pilih Layanan</h5>
          <p class="text-secondary small mb-0">Tentukan kategori yang Anda perlukan, mulai dari AC, listrik, kulkas, pipa, hingga mesin cuci.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom p-4 h-100 border-0 bg-light">
          <div class="step-number-badge">2</div>
          <h5 class="fw-bold text-dark mb-2">Jelaskan Kerusakan</h5>
          <p class="text-secondary small mb-0">Unggah foto kondisi kerusakan dan jelaskan gejala yang terjadi agar teknisi membawa suku cadang tepat.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom p-4 h-100 border-0 bg-light">
          <div class="step-number-badge">3</div>
          <h5 class="fw-bold text-dark mb-2">Pilih Teknisi</h5>
          <p class="text-secondary small mb-0">Pilih mitra berdasarkan rating dan ulasan, atau gunakan sistem penugasan otomatis cerdas kami.</p>
        </div>
      </div>

      <div class="col-md-6 col-lg-3">
        <div class="card card-custom p-4 h-100 border-0 bg-light">
          <div class="step-number-badge">4</div>
          <h5 class="fw-bold text-dark mb-2">Masalah Tuntas</h5>
          <p class="text-secondary small mb-0">Teknisi tiba di lokasi, memperbaiki tuntas dengan rapi, dan dilindungi jaminan garansi layanan.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Teknisi Rekomendasi Showcase -->
<section class="section-padding bg-light">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-end justify-content-between gap-3 mb-5">
      <div>
        <span class="badge-soft-success small mb-2 d-inline-block">Mitra Unggulan</span>
        <h2 class="fw-bold text-dark mb-1">Teknisi Rekomendasi Terdekat</h2>
        <p class="text-secondary mb-0">Mitra berkinerja terbaik dengan rating tinggi dan ulasan kepuasan pelanggan bintang lima.</p>
      </div>
      <a href="{{ url('/customer/technicians') }}" class="btn btn-outline-custom text-nowrap">
        Lihat Semua Teknisi <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>

    <div class="row g-4">
      <div class="col-md-6 col-lg-4">
        <x-technician-card 
          id="1"
          name="Budi Santoso"
          specialty="Teknisi AC & Pendingin"
          rating="4.9"
          reviews="142"
          jobs="284"
          distance="2.4 km"
          :available="true"
          photo="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=200&auto=format&fit=crop&q=80"
        />
      </div>

      <div class="col-md-6 col-lg-4">
        <x-technician-card 
          id="2"
          name="I Made Agus"
          specialty="Master Kelistrikan & Panel"
          rating="4.8"
          reviews="98"
          jobs="192"
          distance="3.1 km"
          :available="true"
          photo="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80"
        />
      </div>

      <div class="col-md-6 col-lg-4">
        <x-technician-card 
          id="3"
          name="Hendro Wijaya"
          specialty="Ahli Plumbing & Saluran Pipa"
          rating="5.0"
          reviews="165"
          jobs="310"
          distance="1.8 km"
          :available="true"
          photo="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80"
        />
      </div>
    </div>
  </div>
</section>

<!-- FAQ Bantuan Ringkas -->
<section class="section-padding bg-white" id="faq">
  <div class="container">
    <div class="text-center max-w-700 mx-auto mb-5">
      <span class="badge-soft-primary small mb-2 d-inline-block">Pusat Informasi</span>
      <h2 class="fw-bold text-dark mb-2">Pertanyaan yang Sering Diajukan</h2>
      <p class="text-secondary">Jawaban atas pertanyaan umum seputar pemesanan dan garansi perbaikan BetulinAja.</p>
    </div>

    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion accordion-flush" id="accordionFaq">
          <div class="accordion-item border rounded-3 mb-3 p-2">
            <h2 class="accordion-header" id="headingOne">
              <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                Apakah estimasi biaya di awal sudah pasti?
              </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionFaq">
              <div class="accordion-body text-secondary small">
                Estimasi biaya mencakup biaya dasar dan ongkos perjalanan. Jika saat pemeriksaan di lokasi ditemukan pergantian suku cadang atau tingkat kerusakan khusus, teknisi akan memberikan penjelasan rincian sebelum memulai pengerjaan.
              </div>
            </div>
          </div>

          <div class="accordion-item border rounded-3 mb-3 p-2">
            <h2 class="accordion-header" id="headingTwo">
              <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Bagaimana jaminan keamanan dan garansi perbaikan?
              </button>
            </h2>
            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionFaq">
              <div class="accordion-body text-secondary small">
                Semua teknisi telah melewati verifikasi dokumen identitas resmi dan uji keterampilan. Layanan perbaikan BetulinAja dilengkapi garansi pengerjaan hingga 30 hari untuk masalah yang sama.
              </div>
            </div>
          </div>

          <div class="accordion-item border rounded-3 mb-3 p-2">
            <h2 class="accordion-header" id="headingThree">
              <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Berapa lama teknisi akan sampai ke lokasi saya?
              </button>
            </h2>
            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionFaq">
              <div class="accordion-body text-secondary small">
                Untuk pemesanan langsung (instan), teknisi terdekat umumnya tiba dalam 20 hingga 45 menit tergantung kondisi lalu lintas. Anda dapat memantau perjalanan teknisi melalui halaman live tracking.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================================
     TIM PENGEMBANG & INOVATOR (UNIVERSITAS INSAN PEMBANGUNAN INDONESIA)
     ========================================================================= -->
<section class="section-padding bg-light border-top border-bottom py-5" id="tim-pengembang">
  <div class="container">
    <div class="text-center max-w-700 mx-auto mb-5">
      <div class="d-inline-flex align-items-center gap-2 bg-white px-3 py-1 rounded-pill border shadow-sm mb-3">
        <i class="bi bi-mortarboard-fill text-primary"></i>
        <span class="small fw-semibold text-dark">Karya Mahasiswa Berprestasi</span>
      </div>
      <h2 class="fw-bold text-dark mb-2">Tim Pendiri & Pengembang Web</h2>
      <p class="text-secondary mb-1">
        Inovasi platform marketplace jasa service dan perbaikan rumah
      </p>
      <h6 class="fw-bold text-primary mb-0">
        <i class="bi bi-building me-1"></i> Universitas Insan Pembangunan Indonesia (UNIPI)
      </h6>
    </div>

    <div class="row g-4 justify-content-center">
      <!-- 1. Maulana Rizki (Penemu) -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 hover-card transition-all" style="background: #ffffff;">
          <div class="position-relative mx-auto mb-3">
            <div class="text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" style="width: 88px; height: 88px; background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
              <i class="bi bi-lightbulb-fill fs-2"></i>
            </div>
            <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-warning text-dark px-3 py-1 rounded-pill small shadow-sm fw-bold">
              Penemu
            </span>
          </div>

          <h5 class="fw-bold text-dark mb-1">Maulana Rizki</h5>
          <span class="text-secondary small fw-medium d-block mb-3">Penemu & Inisiator Ide Platform</span>

          <div class="p-3 bg-light rounded-3 mb-3 text-start">
            <div class="small text-muted mb-1"><i class="bi bi-award me-1 text-warning"></i> Peran Kunci:</div>
            <p class="small text-secondary mb-0">
              Penggagas konsep awal, riset kebutuhan pasar, serta perancangan model bisnis ekosistem jasa perbaikan BetulinAja.
            </p>
          </div>

          <div class="mt-auto pt-2 border-top">
            <span class="badge bg-light text-secondary border small">
              <i class="bi bi-mortarboard me-1 text-primary"></i> Univ. Insan Pembangunan Indonesia
            </span>
          </div>
        </div>
      </div>

      <!-- 2. Iqbal Adityajaya (Pembuat dan Pengembang) -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border border-2 border-primary shadow rounded-4 text-center p-4 hover-card transition-all position-relative" style="background: #ffffff;">
          <div class="position-absolute top-0 end-0 m-3">
            <span class="badge bg-primary text-white rounded-pill px-3 py-1 small fw-bold">
              <i class="bi bi-star-fill text-warning me-1"></i> Lead Creator
            </span>
          </div>

          <div class="position-relative mx-auto mb-3">
            <div class="text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" style="width: 88px; height: 88px; background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);">
              <i class="bi bi-code-slash fs-2"></i>
            </div>
            <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-primary text-white px-3 py-1 rounded-pill small shadow-sm fw-bold">
              Pembuat & Pengembang
            </span>
          </div>

          <h5 class="fw-bold text-dark mb-1">Iqbal Adityajaya</h5>
          <span class="text-primary small fw-semibold d-block mb-3">Lead Fullstack Developer & System Architect</span>

          <div class="p-3 bg-soft-primary rounded-3 mb-3 text-start border border-primary-subtle">
            <div class="small text-primary fw-semibold mb-1"><i class="bi bi-cpu me-1"></i> Peran Kunci:</div>
            <p class="small text-secondary mb-0">
              Perancangan sistem penuh, coding backend Laravel, database relational, integrasi API, dan antarmuka UI/UX responsive.
            </p>
          </div>

          <div class="mt-auto pt-2 border-top d-flex flex-column gap-2">
            <a href="https://wa.me/6285714310516" target="_blank" class="btn btn-outline-success btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1 rounded-pill">
              <i class="bi bi-whatsapp"></i> 085714310516 (Kontak)
            </a>
            <span class="badge bg-light text-secondary border small">
              <i class="bi bi-mortarboard me-1 text-primary"></i> Univ. Insan Pembangunan Indonesia
            </span>
          </div>
        </div>
      </div>

      <!-- 3. Sapriadi Sandy (CEO) -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm rounded-4 text-center p-4 hover-card transition-all" style="background: #ffffff;">
          <div class="position-relative mx-auto mb-3">
            <div class="text-white rounded-circle d-flex align-items-center justify-content-center mx-auto shadow" style="width: 88px; height: 88px; background: linear-gradient(135deg, #10B981 0%, #047857 100%);">
              <i class="bi bi-briefcase-fill fs-2"></i>
            </div>
            <span class="position-absolute bottom-0 start-50 translate-middle-x badge bg-success text-white px-3 py-1 rounded-pill small shadow-sm fw-bold">
              Chief Executive Officer
            </span>
          </div>

          <h5 class="fw-bold text-dark mb-1">Sapriadi Sandy</h5>
          <span class="text-secondary small fw-medium d-block mb-3">CEO & Strategic Lead</span>

          <div class="p-3 bg-light rounded-3 mb-3 text-start">
            <div class="small text-muted mb-1"><i class="bi bi-bar-chart-line me-1 text-success"></i> Peran Kunci:</div>
            <p class="small text-secondary mb-0">
              Pemimpin arah strategis platform, koordinasi tim eksekutif, kemitraan mitra teknisi, dan tata kelola operasional bisnis.
            </p>
          </div>

          <div class="mt-auto pt-2 border-top">
            <span class="badge bg-light text-secondary border small">
              <i class="bi bi-mortarboard me-1 text-primary"></i> Univ. Insan Pembangunan Indonesia
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Call to Action Banner -->
<section class="py-5" style="background: linear-gradient(135deg, #14213D 0%, #1E3A8A 100%);">
  <div class="container text-center text-white py-4">
    <div class="badge-soft-primary bg-white text-primary mb-3 py-1 px-3 fw-bold small">
      Solusi Perbaikan Cepat
    </div>
    <h2 class="display-6 fw-bold mb-3">Butuh Bantuan Sekarang?</h2>
    <p class="lead mb-4 text-white-50 mx-auto" style="max-width: 600px;">
      Pesan teknisi terpercaya dan selesaikan masalah rumah dengan lebih mudah, transparan, dan bergaransi.
    </p>
    <div class="d-flex flex-wrap justify-content-center gap-3">
      <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom btn-lg px-4 shadow">
        <i class="bi bi-tools me-2"></i> Pesan Sekarang
      </a>
      <a href="{{ url('/partner/login') }}" class="btn btn-outline-light btn-lg px-4" style="border-radius: var(--radius-md);">
        <i class="bi bi-briefcase me-2"></i> Gabung Jadi Mitra Teknisi
      </a>
    </div>
  </div>
</section>
@endsection
