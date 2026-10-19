@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
@endpush

@section('content')
<div class="admin-wrapper">

  <!-- Desktop Dark Navy Sidebar -->
  <aside class="admin-sidebar d-none d-lg-flex">
    <div class="admin-sidebar-brand d-flex align-items-center justify-content-between">
      <a class="brand-logo" href="{{ url('/admin/dashboard') }}">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1" style="width: 30px; height: 30px;">
          <i class="bi bi-tools fs-6"></i>
        </div>
        <span>Betulin<span class="text-primary">Aja</span></span>
      </a>
      <span class="badge-admin">ADMIN</span>
    </div>

    <!-- Admin Nav -->
    <nav class="admin-nav">
      <div class="admin-nav-header">Menu Utama</div>
      <a href="{{ url('/admin/dashboard') }}" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i>
        <span>Dashboard</span>
      </a>
      <a href="{{ url('/admin/orders') }}" class="admin-nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
        <i class="bi bi-receipt-cutoff"></i>
        <span>Manajemen Pesanan</span>
      </a>
      <a href="{{ url('/admin/technicians') }}" class="admin-nav-link {{ request()->is('admin/technicians*') ? 'active' : '' }}">
        <i class="bi bi-person-badge"></i>
        <span>Mitra Teknisi</span>
      </a>
      <a href="{{ url('/admin/customers') }}" class="admin-nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
        <i class="bi bi-people"></i>
        <span>Pelanggan</span>
      </a>

      <div class="admin-nav-header mt-2">Katalog & Tarif</div>
      <a href="{{ url('/admin/services') }}" class="admin-nav-link {{ request()->is('admin/services*') ? 'active' : '' }}">
        <i class="bi bi-tags"></i>
        <span>Layanan & Kategori</span>
      </a>
      <a href="{{ url('/admin/pricing') }}" class="admin-nav-link {{ request()->is('admin/pricing*') ? 'active' : '' }}">
        <i class="bi bi-cash-stack"></i>
        <span>Pengaturan Tarif</span>
      </a>

      <div class="admin-nav-header mt-2">Analisis & Sistem</div>
      <a href="{{ url('/admin/reviews') }}" class="admin-nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
        <i class="bi bi-star"></i>
        <span>Ulasan & Rating</span>
      </a>
      <a href="{{ url('/admin/reports') }}" class="admin-nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
        <i class="bi bi-graph-up"></i>
        <span>Laporan & Ekspor</span>
      </a>
      <a href="{{ url('/admin/settings') }}" class="admin-nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
        <i class="bi bi-gear"></i>
        <span>Pengaturan Sistem</span>
      </a>

      <div class="admin-nav-header mt-3">Tautan Cepat Demo</div>
      <a href="{{ url('/') }}" class="admin-nav-link text-white-50">
        <i class="bi bi-box-arrow-up-right"></i>
        <span>Lihat Website User</span>
      </a>
      <a href="{{ url('/partner/dashboard') }}" class="admin-nav-link text-white-50">
        <i class="bi bi-tools"></i>
        <span>Lihat Portal Mitra</span>
      </a>
    </nav>

    <!-- Admin User Info -->
    <div class="p-3 border-top border-secondary-subtle mt-auto" style="border-color: rgba(255,255,255,0.08) !important;">
      <div class="d-flex align-items-center gap-2">
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px;">
          AD
        </div>
        <div class="overflow-hidden">
          <span class="d-block fw-semibold text-white small text-truncate">Administrator</span>
          <span class="d-block text-white-50 text-truncate" style="font-size: 0.72rem;">admin@betulinaja.id</span>
        </div>
      </div>
    </div>
  </aside>

  <!-- Mobile Offcanvas Sidebar for Admin -->
  <div class="offcanvas offcanvas-start bg-dark text-white" tabindex="-1" id="adminOffcanvas" aria-labelledby="adminOffcanvasLabel" style="background-color: var(--dark-navy) !important;">
    <div class="offcanvas-header border-bottom border-secondary">
      <div class="brand-logo" id="adminOffcanvasLabel">
        <span class="fw-bold">Betulin<span class="text-primary">Aja</span> Admin</span>
      </div>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0 d-flex flex-column">
      <nav class="admin-nav">
        <a href="{{ url('/admin/dashboard') }}" class="admin-nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
          <i class="bi bi-speedometer2"></i>
          <span>Dashboard</span>
        </a>
        <a href="{{ url('/admin/orders') }}" class="admin-nav-link {{ request()->is('admin/orders*') ? 'active' : '' }}">
          <i class="bi bi-receipt-cutoff"></i>
          <span>Pesanan</span>
        </a>
        <a href="{{ url('/admin/technicians') }}" class="admin-nav-link {{ request()->is('admin/technicians*') ? 'active' : '' }}">
          <i class="bi bi-person-badge"></i>
          <span>Mitra Teknisi</span>
        </a>
        <a href="{{ url('/admin/customers') }}" class="admin-nav-link {{ request()->is('admin/customers*') ? 'active' : '' }}">
          <i class="bi bi-people"></i>
          <span>Pelanggan</span>
        </a>
        <a href="{{ url('/admin/services') }}" class="admin-nav-link {{ request()->is('admin/services*') ? 'active' : '' }}">
          <i class="bi bi-tags"></i>
          <span>Layanan & Kategori</span>
        </a>
        <a href="{{ url('/admin/pricing') }}" class="admin-nav-link {{ request()->is('admin/pricing*') ? 'active' : '' }}">
          <i class="bi bi-cash-stack"></i>
          <span>Pengaturan Tarif</span>
        </a>
        <a href="{{ url('/admin/reviews') }}" class="admin-nav-link {{ request()->is('admin/reviews*') ? 'active' : '' }}">
          <i class="bi bi-star"></i>
          <span>Ulasan & Rating</span>
        </a>
        <a href="{{ url('/admin/reports') }}" class="admin-nav-link {{ request()->is('admin/reports*') ? 'active' : '' }}">
          <i class="bi bi-graph-up"></i>
          <span>Laporan</span>
        </a>
        <a href="{{ url('/admin/settings') }}" class="admin-nav-link {{ request()->is('admin/settings*') ? 'active' : '' }}">
          <i class="bi bi-gear"></i>
          <span>Pengaturan</span>
        </a>
        <hr class="border-secondary my-2">
        <a href="{{ url('/') }}" class="admin-nav-link">
          <i class="bi bi-arrow-left"></i>
          <span>Website Utama</span>
        </a>
      </nav>
    </div>
  </div>

  <!-- Admin Main Work Area -->
  <div class="admin-main">
    <!-- Topbar -->
    <header class="admin-topbar">
      <div class="d-flex align-items-center gap-3">
        <!-- Offcanvas Mobile Button -->
        <button class="btn btn-outline-custom p-2 d-lg-none touch-target" type="button" data-bs-toggle="offcanvas" data-bs-target="#adminOffcanvas" aria-controls="adminOffcanvas">
          <i class="bi bi-list fs-5"></i>
        </button>
        <div>
          <h5 class="fw-bold text-dark mb-0 fs-6 fs-sm-5">@yield('page_title', 'Admin Dashboard')</h5>
          <span class="text-secondary small d-none d-md-block">Kelola operasional marketplace BetulinAja secara menyeluruh</span>
        </div>
      </div>

      <!-- Topbar Actions -->
      <div class="d-flex align-items-center gap-2">
        <div class="d-none d-md-block me-2">
          <span class="badge-soft-success py-1 px-3 small d-inline-flex align-items-center gap-1">
            <span class="status-dot online"></span> Server Online
          </span>
        </div>

        <button type="button" class="btn btn-outline-custom position-relative p-2 touch-target" onclick="BetulinAja.showToast('Notifikasi Admin', 'Ada 2 verifikasi mitra teknisi baru menunggu review.', 'info')">
          <i class="bi bi-bell fs-6"></i>
          <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
        </button>

        <a href="{{ url('/') }}" class="btn btn-soft-primary btn-sm d-none d-sm-inline-flex align-items-center gap-1">
          <i class="bi bi-globe"></i> Web
        </a>
      </div>
    </header>

    <!-- Admin Content Area -->
    <main class="p-3 p-md-4 flex-grow-1">
      @yield('admin_content')
    </main>
  </div>

</div>
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/admin.js') }}"></script>
@endpush
