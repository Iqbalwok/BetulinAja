<nav class="navbar navbar-expand-lg navbar-customer sticky-top shadow-sm py-2">
  <div class="container d-flex align-items-center justify-content-between">
    <!-- Brand Logo -->
    <a class="navbar-brand navbar-brand-logo py-0 me-3" href="{{ url('/') }}">
      <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1 shadow-sm" style="width: 32px; height: 32px;">
        <i class="bi bi-tools fs-6"></i>
      </div>
      <span>Betulin<span class="brand-highlight">Aja</span></span>
    </a>

    <!-- Desktop Nav Links (Clean & Ringkas) -->
    <div class="d-none d-lg-flex align-items-center gap-1 mx-auto">
      <a class="nav-link nav-link-custom px-3 py-2 {{ request()->is('/') ? 'active fw-semibold' : '' }}" href="{{ url('/') }}">
        Beranda
      </a>
      <a class="nav-link nav-link-custom px-3 py-2 {{ request()->is('customer/services*') ? 'active fw-semibold' : '' }}" href="{{ url('/customer/services') }}">
        Layanan
      </a>
      <a class="nav-link nav-link-custom px-3 py-2 {{ request()->is('customer/technicians*') ? 'active fw-semibold' : '' }}" href="{{ url('/customer/technicians') }}">
        Teknisi
      </a>
      <a class="nav-link nav-link-custom px-3 py-2 {{ request()->is('customer/estimate*') ? 'active fw-semibold' : '' }}" href="{{ url('/customer/estimate') }}">
        Simulasi Biaya
      </a>
    </div>

    <!-- Right Controls: Pesanan, Pesan Jasa & Toggle Menu Samping -->
    <div class="d-flex align-items-center gap-2">
      <!-- Pesanan Saya (Desktop) -->
      <a href="{{ url('/customer/order-history') }}" class="btn btn-outline-custom btn-sm px-3 py-2 d-none d-sm-inline-flex align-items-center gap-1" style="font-size: 0.875rem;">
        <i class="bi bi-receipt"></i>
        <span>Pesanan Saya</span>
      </a>

      <!-- Tombol CTA Pesan Jasa -->
      <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom btn-sm px-3 py-2 d-inline-flex align-items-center gap-1 shadow-sm fw-semibold" style="font-size: 0.875rem;">
        <i class="bi bi-plus-circle"></i>
        <span>Pesan Jasa</span>
      </a>

      <!-- Tombol Menu Samping (Desktop & Mobile) -->
      <button class="btn btn-outline-custom p-2 d-inline-flex align-items-center justify-content-center touch-target ms-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenuSamping" aria-controls="sidebarMenuSamping" title="Buka Menu Samping">
        <i class="bi bi-list fs-5 text-dark"></i>
      </button>
    </div>
  </div>
</nav>

<!-- ==============================================================
     OFFCANVAS MENU SAMPING (SIDEBAR DRAWER)
     ============================================================== -->
<div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="sidebarMenuSamping" aria-labelledby="sidebarMenuSampingLabel" style="width: 320px; max-width: 85vw; border-left: 1px solid #e2e8f0;">
  <!-- Offcanvas Header -->
  <div class="offcanvas-header bg-light border-bottom py-3">
    <div class="d-flex align-items-center gap-2" id="sidebarMenuSampingLabel">
      <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1" style="width: 30px; height: 30px;">
        <i class="bi bi-tools small"></i>
      </div>
      <div>
        <h6 class="fw-bold mb-0 text-dark">Betulin<span class="text-primary">Aja</span></h6>
        <span class="text-secondary" style="font-size: 0.72rem;">Menu & Layanan Lengkap</span>
      </div>
    </div>
    <button type="button" class="btn-close text-reset shadow-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <!-- Offcanvas Body -->
  <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
    <div class="p-3">
      <!-- Call to Action Card -->
      <div class="bg-soft-primary p-3 rounded-3 mb-3 border border-primary-subtle">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <span class="badge-soft-success py-1 px-2 small">Teknisi Siaga</span>
          <span class="text-muted small"><i class="bi bi-clock me-1"></i>07.00 - 21.00</span>
        </div>
        <h6 class="fw-bold text-dark mb-1">Ada Kerusakan di Rumah?</h6>
        <p class="text-secondary small mb-2">Teknisi profesional siap meluncur ke lokasi Anda.</p>
        <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom btn-sm w-100 fw-semibold">
          <i class="bi bi-plus-circle me-1"></i> Pesan Jasa Sekarang
        </a>
      </div>

      <!-- Menu Section: Navigasi Pelanggan -->
      <div class="mb-3">
        <div class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Menu Utama</div>
        <div class="list-group list-group-flush border-0">
          <a href="{{ url('/') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('/') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-house-door text-primary fs-6"></i>
            <span>Beranda</span>
          </a>
          <a href="{{ url('/customer/services') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/services*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-grid text-primary fs-6"></i>
            <span>Katalog Layanan</span>
          </a>
          <a href="{{ url('/customer/order-history') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/order-history*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-clock-history text-primary fs-6"></i>
            <span>Riwayat Pesanan</span>
          </a>
          <a href="{{ url('/customer/tracking') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/tracking*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-geo-alt text-primary fs-6"></i>
            <span>Live Tracking Teknisi</span>
          </a>
          <a href="{{ url('/customer/technicians') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/technicians*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-people text-primary fs-6"></i>
            <span>Direktori Teknisi</span>
          </a>
          <a href="{{ url('/customer/estimate') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/estimate*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-calculator text-primary fs-6"></i>
            <span>Simulasi Biaya Service</span>
          </a>
          <a href="{{ url('/customer/location') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/location*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-map text-primary fs-6"></i>
            <span>Cek Jangkauan Wilayah</span>
          </a>
          <a href="{{ url('/customer/profile') }}" class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-2 rounded-2 border-0 {{ request()->is('customer/profile*') ? 'bg-light text-primary fw-semibold' : 'text-dark' }}">
            <i class="bi bi-person-circle text-primary fs-6"></i>
            <span>Profil Pengguna</span>
          </a>
        </div>
      </div>

      <!-- Menu Section: Portal Mitra & Admin -->
      <div class="mb-3">
        <div class="text-uppercase text-muted fw-bold mb-2" style="font-size: 0.7rem; letter-spacing: 0.05em;">Akses Portal Khusus</div>
        <div class="d-flex flex-column gap-2">
          <a href="{{ url('/partner/dashboard') }}" class="text-decoration-none p-2 rounded-2 border bg-light d-flex align-items-center justify-content-between hover-card">
            <div class="d-flex align-items-center gap-2">
              <div class="bg-success text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i class="bi bi-tools small"></i>
              </div>
              <div>
                <span class="d-block fw-semibold text-dark small">Portal Mitra Teknisi</span>
                <span class="d-block text-muted" style="font-size: 0.72rem;">Pesanan, Pengerjaan & Saldo</span>
              </div>
            </div>
            <i class="bi bi-chevron-right text-muted small"></i>
          </a>

          <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none p-2 rounded-2 border bg-light d-flex align-items-center justify-content-between hover-card">
            <div class="d-flex align-items-center gap-2">
              <div class="bg-danger text-white rounded p-1 d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                <i class="bi bi-shield-lock small"></i>
              </div>
              <div>
                <span class="d-block fw-semibold text-dark small">Portal Administrator</span>
                <span class="d-block text-muted" style="font-size: 0.72rem;">Monitoring, Verifikasi & Tarif</span>
              </div>
            </div>
            <i class="bi bi-chevron-right text-muted small"></i>
          </a>
        </div>
      </div>
    </div>

    <!-- Offcanvas Footer: Creator Info -->
    <div class="p-3 bg-light border-top mt-auto">
      <div class="mb-2">
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle small mb-1">
          <i class="bi bi-mortarboard me-1"></i> Univ. Insan Pembangunan Indonesia
        </span>
        <div class="small fw-semibold text-dark">Tim Pengembang:</div>
        <div class="text-secondary" style="font-size: 0.75rem; line-height: 1.5;">
          &bull; <strong>Maulana Rizki</strong> (Penemu)<br>
          &bull; <strong>Iqbal Adityajaya</strong> (Pembuat & Pengembang)<br>
          &bull; <strong>Sapriadi Sandy</strong> (CEO)
        </div>
      </div>
      <div class="d-flex align-items-center gap-2 pt-2 border-top">
        <i class="bi bi-whatsapp text-success fs-5"></i>
        <div>
          <span class="d-block fw-semibold text-dark" style="font-size: 0.75rem;">Kontak WhatsApp:</span>
          <a href="https://wa.me/6285714310516" target="_blank" class="text-decoration-none text-success small fw-bold">
            085714310516
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

