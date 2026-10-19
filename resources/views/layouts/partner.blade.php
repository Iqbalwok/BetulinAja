@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/partner.css') }}">
@endpush

@section('content')
<div class="partner-wrapper">

  <!-- Desktop Sidebar -->
  <aside class="partner-sidebar d-none d-lg-flex">
    <div class="partner-sidebar-brand d-flex align-items-center justify-content-between">
      <a class="navbar-brand-logo" href="{{ url('/partner/dashboard') }}" style="font-size: 1.25rem;">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1 me-1" style="width: 28px; height: 28px;">
          <i class="bi bi-tools fs-6"></i>
        </div>
        <span>Betulin<span class="brand-highlight">Aja</span></span>
      </a>
      <span class="badge-soft-success small py-1 px-2" style="font-size: 0.7rem;">Mitra</span>
    </div>

    <!-- Partner Profile Snippet -->
    <div class="p-3 border-bottom bg-light">
      <div class="d-flex align-items-center gap-3">
        <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=120&auto=format&fit=crop&q=80" alt="Budi Santoso" class="rounded-circle object-fit-cover shadow-sm" style="width: 44px; height: 44px;">
        <div class="overflow-hidden">
          <h6 class="fw-bold text-dark mb-0 fs-6 text-truncate">Budi Santoso</h6>
          <span class="text-secondary small d-block" style="font-size: 0.75rem;">Teknisi AC & Pendingin</span>
          <div class="d-flex align-items-center gap-1 text-warning small" style="font-size: 0.75rem;">
            <i class="bi bi-star-fill"></i> 4.9 <span class="text-muted">(142)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Links -->
    <nav class="partner-nav">
      <a href="{{ url('/partner/dashboard') }}" class="partner-nav-link {{ request()->is('partner/dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
      <a href="{{ url('/partner/orders') }}" class="partner-nav-link {{ request()->is('partner/orders') ? 'active' : '' }}">
        <i class="bi bi-inbox"></i>
        <span>Pesanan Masuk</span>
        <span class="badge bg-danger rounded-pill ms-auto px-2" style="font-size: 0.7rem;">3 Baru</span>
      </a>
      <a href="{{ url('/partner/active-job') }}" class="partner-nav-link {{ request()->is('partner/active-job') ? 'active' : '' }}">
        <i class="bi bi-play-circle"></i>
        <span>Pekerjaan Aktif</span>
        <span class="badge bg-warning text-dark rounded-pill ms-auto px-2" style="font-size: 0.7rem;">1 Aktif</span>
      </a>
      <a href="{{ url('/partner/earnings') }}" class="partner-nav-link {{ request()->is('partner/earnings') ? 'active' : '' }}">
        <i class="bi bi-wallet2"></i>
        <span>Pendapatan</span>
      </a>
      <a href="{{ url('/partner/profile') }}" class="partner-nav-link {{ request()->is('partner/profile') ? 'active' : '' }}">
        <i class="bi bi-person-gear"></i>
        <span>Profil & Keahlian</span>
      </a>
      <hr class="my-2 border-secondary-subtle">
      <a href="{{ url('/') }}" class="partner-nav-link text-secondary">
        <i class="bi bi-arrow-left"></i>
        <span>Lihat Website Utama</span>
      </a>
    </nav>

    <!-- Logout Snippet -->
    <div class="p-3 border-top mt-auto">
      <a href="{{ url('/partner/login') }}" class="btn btn-outline-custom w-100 btn-sm text-danger">
        <i class="bi bi-box-arrow-right me-1"></i> Keluar Akun
      </a>
    </div>
  </aside>

  <!-- Mobile Offcanvas Sidebar -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="partnerOffcanvas" aria-labelledby="partnerOffcanvasLabel">
    <div class="offcanvas-header border-bottom">
      <div class="navbar-brand-logo" id="partnerOffcanvasLabel" style="font-size: 1.2rem;">
        <span>Betulin<span class="brand-highlight">Aja</span> Mitra</span>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
      <div class="p-3 bg-light border-bottom">
        <div class="d-flex align-items-center gap-3">
          <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=120&auto=format&fit=crop&q=80" alt="Budi Santoso" class="rounded-circle object-fit-cover" style="width: 44px; height: 44px;">
          <div>
            <h6 class="fw-bold text-dark mb-0">Budi Santoso</h6>
            <small class="text-secondary">Teknisi AC & Pendingin</small>
          </div>
        </div>
      </div>
      <nav class="partner-nav">
        <a href="{{ url('/partner/dashboard') }}" class="partner-nav-link {{ request()->is('partner/dashboard') ? 'active' : '' }}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ url('/partner/orders') }}" class="partner-nav-link {{ request()->is('partner/orders') ? 'active' : '' }}">
          <i class="bi bi-inbox"></i>
          <span>Pesanan Masuk</span>
        </a>
        <a href="{{ url('/partner/active-job') }}" class="partner-nav-link {{ request()->is('partner/active-job') ? 'active' : '' }}">
          <i class="bi bi-play-circle"></i>
          <span>Pekerjaan Aktif</span>
        </a>
        <a href="{{ url('/partner/earnings') }}" class="partner-nav-link {{ request()->is('partner/earnings') ? 'active' : '' }}">
          <i class="bi bi-wallet2"></i>
          <span>Pendapatan</span>
        </a>
        <a href="{{ url('/partner/profile') }}" class="partner-nav-link {{ request()->is('partner/profile') ? 'active' : '' }}">
          <i class="bi bi-person-gear"></i>
          <span>Profil & Keahlian</span>
        </a>
        <hr class="my-2 border-secondary-subtle">
        <a href="{{ url('/') }}" class="partner-nav-link">
          <i class="bi bi-arrow-left"></i>
          <span>Website Utama</span>
        </a>
      </nav>
      <div class="p-3 border-top mt-auto">
        <a href="{{ url('/partner/login') }}" class="btn btn-outline-danger w-100 btn-sm">
          <i class="bi bi-box-arrow-right me-1"></i> Keluar
        </a>
      </div>
    </div>
  </div>

  <!-- Main Work Area -->
  <div class="partner-main">
    <!-- Topbar -->
    <header class="partner-topbar">
      <div class="d-flex align-items-center gap-3">
        <!-- Offcanvas button on mobile -->
        <button class="btn btn-outline-custom p-2 d-lg-none touch-target" type="button" data-bs-toggle="offcanvas" data-bs-target="#partnerOffcanvas" aria-controls="partnerOffcanvas">
          <i class="bi bi-list fs-5"></i>
        </button>
        <div>
          <h5 class="fw-bold text-dark mb-0 d-none d-sm-block">@yield('page_title', 'Dashboard Mitra')</h5>
          <span class="text-secondary small d-none d-md-block">Selamat bertugas, berikan layanan terbaik untuk pelanggan!</span>
        </div>
      </div>

      <!-- Right items: Online Switch & Notification -->
      <div class="d-flex align-items-center gap-2 gap-sm-3">
        <!-- Online/Offline Switch Component -->
        <div class="status-toggle-card">
          <span class="status-dot online" id="partner-status-dot"></span>
          <span id="partner-status-text" class="fw-bold text-success small d-none d-sm-inline">Online — Siap Terima</span>
          <label class="custom-switch m-0" title="Ubah status ketersediaan">
            <input type="checkbox" id="partner-status-toggle" checked>
            <span class="slider-round"></span>
          </label>
        </div>

        <button type="button" class="btn btn-outline-custom position-relative p-2 touch-target" onclick="BetulinAja.showToast('Notifikasi', 'Ada 3 pesanan baru di sekitar lokasi Anda!', 'info')">
          <i class="bi bi-bell fs-6"></i>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
        </button>
      </div>
    </header>

    <!-- Partner Page Content -->
    <main class="p-3 p-md-4 flex-grow-1">
      @yield('partner_content')
    </main>
  </div>

  <!-- Mobile Bottom Navigation Bar -->
  <nav class="partner-bottom-nav">
    <a href="{{ url('/partner/dashboard') }}" class="bottom-nav-item {{ request()->is('partner/dashboard') ? 'active' : '' }}">
      <i class="bi bi-grid"></i>
      <span>Beranda</span>
    </a>
    <a href="{{ url('/partner/orders') }}" class="bottom-nav-item {{ request()->is('partner/orders') ? 'active' : '' }}">
      <i class="bi bi-inbox"></i>
      <span>Pesanan</span>
    </a>
    <a href="{{ url('/partner/active-job') }}" class="bottom-nav-item {{ request()->is('partner/active-job') ? 'active' : '' }}">
      <i class="bi bi-play-circle"></i>
      <span>Kerjaan</span>
    </a>
    <a href="{{ url('/partner/earnings') }}" class="bottom-nav-item {{ request()->is('partner/earnings') ? 'active' : '' }}">
      <i class="bi bi-wallet2"></i>
      <span>Pendapatan</span>
    </a>
    <a href="{{ url('/partner/profile') }}" class="bottom-nav-item {{ request()->is('partner/profile') ? 'active' : '' }}">
      <i class="bi bi-person"></i>
      <span>Profil</span>
    </a>
  </nav>

</div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/partner.js') }}"></script>
@endpush
