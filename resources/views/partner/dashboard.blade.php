@extends('layouts.partner')

@section('title', 'Dashboard Mitra Teknisi — BetulinAja')
@section('page_title', 'Ringkasan & Kinerja Mitra')

@section('partner_content')
<!-- Metrics Overview (Mobile 2 cols, Desktop 4 cols) -->
<div class="row g-3 g-md-4 mb-4">
  <!-- Metric 1: Total Pesanan -->
  <div class="col-6 col-lg-3">
    <div class="card card-custom p-3 p-md-4 h-100">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-medium">Total Pesanan</span>
        <div class="category-icon-box m-0 bg-primary-light text-primary" style="width: 38px; height: 38px; font-size: 1.1rem;">
          <i class="bi bi-tools"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-0">284</h3>
      <span class="text-success small fw-medium mt-1 d-block"><i class="bi bi-arrow-up-right me-1"></i>+12 minggu ini</span>
    </div>
  </div>

  <!-- Metric 2: Pesanan Baru -->
  <div class="col-6 col-lg-3">
    <div class="card card-custom p-3 p-md-4 h-100">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-medium">Pesanan Baru</span>
        <div class="category-icon-box m-0 bg-danger-subtle text-danger" style="width: 38px; height: 38px; font-size: 1.1rem;">
          <i class="bi bi-bell-fill"></i>
        </div>
      </div>
      <h3 class="fw-bold text-danger mb-0">3</h3>
      <span class="text-secondary small mt-1 d-block">Radius &lt; 5 km</span>
    </div>
  </div>

  <!-- Metric 3: Pendapatan Bulan Ini -->
  <div class="col-6 col-lg-3">
    <div class="card card-custom p-3 p-md-4 h-100">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-medium">Pendapatan Bersih</span>
        <div class="category-icon-box m-0 bg-success-subtle text-success" style="width: 38px; height: 38px; font-size: 1.1rem;">
          <i class="bi bi-wallet2"></i>
        </div>
      </div>
      <h4 class="fw-bold text-success mb-0 fs-5 fs-sm-4">Rp4.850.000</h4>
      <span class="text-muted small mt-1 d-block">Bulan Oktober 2026</span>
    </div>
  </div>

  <!-- Metric 4: Rating Kinerja -->
  <div class="col-6 col-lg-3">
    <div class="card card-custom p-3 p-md-4 h-100">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="text-secondary small fw-medium">Rating Pelanggan</span>
        <div class="category-icon-box m-0 bg-warning-subtle text-warning" style="width: 38px; height: 38px; font-size: 1.1rem;">
          <i class="bi bi-star-fill"></i>
        </div>
      </div>
      <h3 class="fw-bold text-dark mb-0">4.9 <span class="text-muted fs-6">/ 5.0</span></h3>
      <span class="text-secondary small mt-1 d-block">Dari 142 Ulasan</span>
    </div>
  </div>
</div>

<!-- Active Ongoing Job Alert Banner -->
<div class="card card-custom p-4 mb-4 border-primary border-2 shadow-sm" style="background: linear-gradient(90deg, #EFF6FF 0%, #FFFFFF 100%);">
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
    <div class="d-flex align-items-center gap-3">
      <div class="category-icon-box m-0 bg-primary text-white flex-shrink-0" style="width: 52px; height: 52px;">
        <i class="bi bi-bicycle fs-4"></i>
      </div>
      <div>
        <div class="d-flex align-items-center gap-2 mb-1">
          <span class="badge-soft-primary px-3 py-1">Pekerjaan Aktif</span>
          <span class="font-monospace fw-bold text-dark small">#ORD-849201</span>
        </div>
        <h5 class="fw-bold text-dark mb-0">Cuci AC Standar (0.5 - 1 PK) &bull; Pelanggan: Ahmad Fauzi</h5>
        <span class="text-secondary small"><i class="bi bi-geo-alt text-danger me-1"></i>Jl. Sudirman No. 45, Kebayoran Baru (2.4 km dari Anda)</span>
      </div>
    </div>
    <div class="d-flex gap-2 w-100 w-md-auto">
      <a href="{{ url('/partner/active-job?order=ORD-849201') }}" class="btn btn-primary-custom flex-fill text-nowrap">
        Buka Lembar Pengerjaan <i class="bi bi-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</div>

<!-- Partner Info & Announcement Slideshow Carousel -->
<div class="mb-4">
  <div id="partnerCarousel" class="carousel slide carousel-fade hero-carousel-container shadow-sm" data-bs-ride="carousel" data-bs-interval="4500" data-bs-pause="hover" style="border-radius: 16px;">
    <!-- Indicators -->
    <div class="carousel-indicators carousel-indicators-pill">
      <button type="button" data-bs-target="#partnerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Info 1: Tips Kinerja"></button>
      <button type="button" data-bs-target="#partnerCarousel" data-bs-slide-to="1" aria-label="Info 2: Insentif & Dompet"></button>
      <button type="button" data-bs-target="#partnerCarousel" data-bs-slide-to="2" aria-label="Info 3: SOP Pelayanan"></button>
    </div>

    <!-- Slides -->
    <div class="carousel-inner">
      <div class="carousel-item active" style="height: 220px; background-image: url('https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=1000&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
        <div class="hero-carousel-overlay" style="background: linear-gradient(90deg, rgba(20, 33, 61, 0.95) 0%, rgba(20, 33, 61, 0.75) 60%, rgba(20, 33, 61, 0.4) 100%);"></div>
        <div class="position-relative p-4 text-white" style="z-index: 2; max-width: 580px;">
          <span class="badge bg-warning text-dark px-3 py-1 rounded-pill small fw-bold mb-2 d-inline-block">
            <i class="bi bi-award-fill me-1"></i> Program Insentif Mitra
          </span>
          <h5 class="fw-bold mb-1">Raih Bonus Tambahan Setiap 10 Pesanan Tuntas!</h5>
          <p class="text-white-50 small mb-2 d-none d-sm-block">Pertahankan rating bintang di atas 4.8 untuk mendapatkan prioritas pesanan radius terdekat.</p>
          <a href="{{ url('/partner/earnings') }}" class="btn btn-primary-custom btn-sm px-3">
            <i class="bi bi-wallet2 me-1"></i> Cek Dompet Saya
          </a>
        </div>
      </div>

      <div class="carousel-item" style="height: 220px; background-image: url('https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=1000&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
        <div class="hero-carousel-overlay" style="background: linear-gradient(90deg, rgba(16, 185, 129, 0.92) 0%, rgba(4, 120, 87, 0.8) 60%, rgba(4, 120, 87, 0.4) 100%);"></div>
        <div class="position-relative p-4 text-white" style="z-index: 2; max-width: 580px;">
          <span class="badge bg-white text-success px-3 py-1 rounded-pill small fw-bold mb-2 d-inline-block">
            <i class="bi bi-shield-check me-1"></i> SOP Kualitas Pelayanan
          </span>
          <h5 class="fw-bold mb-1">Pastikan Foto Sebelum & Sesudah Pengerjaan</h5>
          <p class="text-white-50 small mb-2 d-none d-sm-block">Foto bukti yang jelas mempercepat pencairan saldo dan meningkatkan kepercayaan pelanggan.</p>
          <a href="{{ url('/partner/orders') }}" class="btn btn-light btn-sm px-3 text-dark fw-semibold">
            Lihat Pesanan Masuk
          </a>
        </div>
      </div>

      <div class="carousel-item" style="height: 220px; background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1000&auto=format&fit=crop&q=80'); background-size: cover; background-position: center; position: relative;">
        <div class="hero-carousel-overlay" style="background: linear-gradient(90deg, rgba(37, 99, 235, 0.95) 0%, rgba(29, 78, 216, 0.8) 60%, rgba(29, 78, 216, 0.4) 100%);"></div>
        <div class="position-relative p-4 text-white" style="z-index: 2; max-width: 580px;">
          <span class="badge bg-warning text-dark px-3 py-1 rounded-pill small fw-bold mb-2 d-inline-block">
            <i class="bi bi-geo-alt-fill me-1"></i> Perluasan Area Layanan
          </span>
          <h5 class="fw-bold mb-1">Permintaan Tinggi di Area Tangerang & Jaksel</h5>
          <p class="text-white-50 small mb-2 d-none d-sm-block">Aktifkan status "Online" untuk menerima pesanan panggilan instan terdekat.</p>
          <a href="{{ url('/partner/profile') }}" class="btn btn-outline-light btn-sm px-3">
            Atur Radius Operasional
          </a>
        </div>
      </div>
    </div>

    <!-- Controls -->
    <button class="carousel-control-prev carousel-btn-control" type="button" data-bs-target="#partnerCarousel" data-bs-slide="prev" aria-label="Sebelumnya" style="width: 38px; height: 38px; font-size: 1rem;">
      <i class="bi bi-chevron-left"></i>
    </button>
    <button class="carousel-control-next carousel-btn-control" type="button" data-bs-target="#partnerCarousel" data-bs-slide="next" aria-label="Berikutnya" style="width: 38px; height: 38px; font-size: 1rem;">
      <i class="bi bi-chevron-right"></i>
    </button>
  </div>
</div>

<!-- Incoming Orders List -->
<div class="card card-custom p-4 mb-4">
  <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Pesanan Masuk Baru (Radius Terdekat)</h5>
      <span class="text-secondary small">Terima sebelum dialihkan ke mitra teknisi lainnya (Auto-Timer: 60 Detik).</span>
    </div>
    <a href="{{ url('/partner/orders') }}" class="btn btn-outline-custom btn-sm">
      Lihat Semua (3)
    </a>
  </div>

  <div class="row g-3">
    <!-- Order 1 -->
    <div class="col-12 order-card-item">
      <div class="border rounded-3 p-3 bg-white hover-shadow transition">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-2 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <span class="badge-soft-danger small">Baru Saja Masuk</span>
            <span class="fw-bold font-monospace text-dark">#ORD-892134</span>
            <span class="text-secondary small">&bull; 15 menit lalu</span>
          </div>
          <span class="fw-bold text-success fs-5">Est. Penghasilan: Rp150.000</span>
        </div>

        <div class="row g-3 py-3 align-items-center">
          <div class="col-md-5">
            <h6 class="fw-bold text-dark mb-1">Tambah Freon R32 & Cuci AC</h6>
            <p class="text-secondary small mb-0"><i class="bi bi-geo-alt text-danger me-1"></i>Jl. Senopati No. 12, Kebayoran Baru (Jarak: 1.8 km)</p>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Pelanggan</span>
            <span class="fw-semibold text-dark small">Dewi Kartika &bull; 0813-9988-xxxx</span>
          </div>
          <div class="col-md-3 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end">
              <button type="button" class="btn btn-accent-green btn-sm px-3 btn-accept-order" data-id="ORD-892134">
                <i class="bi bi-check-lg me-1"></i> Terima
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm px-2 btn-reject-order" data-id="ORD-892134" title="Tolak Pesanan">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order 2 -->
    <div class="col-12 order-card-item">
      <div class="border rounded-3 p-3 bg-white">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-2 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <span class="badge-soft-info small">Jadwal Nanti</span>
            <span class="fw-bold font-monospace text-dark">#ORD-892188</span>
            <span class="text-secondary small">&bull; Hari ini, 16:00 WIB</span>
          </div>
          <span class="fw-bold text-success fs-5">Est. Penghasilan: Rp175.000</span>
        </div>

        <div class="row g-3 py-3 align-items-center">
          <div class="col-md-5">
            <h6 class="fw-bold text-dark mb-1">Perbaikan AC Bocor Menetes Indoor</h6>
            <p class="text-secondary small mb-0"><i class="bi bi-geo-alt text-danger me-1"></i>Jl. Wijaya II No. 8, Melawai (Jarak: 2.9 km)</p>
          </div>
          <div class="col-md-4">
            <span class="text-muted small d-block">Pelanggan</span>
            <span class="fw-semibold text-dark small">Bambang S. &bull; 0817-2233-xxxx</span>
          </div>
          <div class="col-md-3 text-md-end">
            <div class="d-flex gap-2 justify-content-md-end">
              <button type="button" class="btn btn-accent-green btn-sm px-3 btn-accept-order" data-id="ORD-892188">
                <i class="bi bi-check-lg me-1"></i> Terima
              </button>
              <button type="button" class="btn btn-outline-danger btn-sm px-2 btn-reject-order" data-id="ORD-892188" title="Tolak Pesanan">
                <i class="bi bi-x-lg"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
