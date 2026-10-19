<footer class="bg-white border-top mt-auto pt-5 pb-4">
  <div class="container">
    <div class="row g-4 mb-5">
      <!-- Col 1: Brand & Tagline -->
      <div class="col-lg-4 col-md-6">
        <a class="navbar-brand-logo mb-3 d-inline-block" href="{{ url('/') }}">
          <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1 me-1" style="width: 32px; height: 32px;">
            <i class="bi bi-tools fs-6"></i>
          </div>
          <span>Betulin<span class="brand-highlight">Aja</span></span>
        </a>
        <p class="text-secondary mb-3" style="font-size: 0.95rem; line-height: 1.6;">
          <strong>"Semua Masalah Rumah, BetulinAja!"</strong><br>
          Platform marketplace jasa service dan perbaikan rumah terpercaya. Kami menghubungkan Anda dengan mitra teknisi profesional berstandar tinggi secara transparan, aman, dan mudah.
        </p>
        <div class="d-flex align-items-center gap-2">
          <span class="badge-soft-success py-1 px-3 small d-inline-flex align-items-center gap-1">
            <i class="bi bi-shield-check"></i> 100% Teknisi Terverifikasi
          </span>
          <span class="badge-soft-primary py-1 px-3 small d-inline-flex align-items-center gap-1">
            <i class="bi bi-star-fill text-warning"></i> Rating 4.9/5.0
          </span>
        </div>
      </div>

      <!-- Col 2: Kategori Layanan -->
      <div class="col-lg-2 col-md-6 col-6">
        <h6 class="fw-bold text-dark mb-3">Layanan Populer</h6>
        <ul class="list-unstyled d-flex flex-direction-column flex-column gap-2 mb-0" style="font-size: 0.9rem;">
          <li><a href="{{ url('/customer/services?cat=ac') }}" class="text-secondary text-decoration-none hover-primary">Service AC & Pendingin</a></li>
          <li><a href="{{ url('/customer/services?cat=elektronik') }}" class="text-secondary text-decoration-none">Perbaikan Elektronik</a></li>
          <li><a href="{{ url('/customer/services?cat=listrik') }}" class="text-secondary text-decoration-none">Instalasi & Listrik</a></li>
          <li><a href="{{ url('/customer/services?cat=plumbing') }}" class="text-secondary text-decoration-none">Plumbing & Pipa</a></li>
          <li><a href="{{ url('/customer/services?cat=kulkas') }}" class="text-secondary text-decoration-none">Service Kulkas & Freezer</a></li>
          <li><a href="{{ url('/customer/services?cat=komputer') }}" class="text-secondary text-decoration-none">Komputer & Laptop</a></li>
        </ul>
      </div>

      <!-- Col 3: Navigasi Cepat -->
      <div class="col-lg-3 col-md-6 col-6">
        <h6 class="fw-bold text-dark mb-3">Akses Platform</h6>
        <ul class="list-unstyled d-flex flex-column gap-2 mb-0" style="font-size: 0.9rem;">
          <li><a href="{{ url('/customer/booking') }}" class="text-secondary text-decoration-none">Form Pemesanan Jasa</a></li>
          <li><a href="{{ url('/customer/estimate') }}" class="text-secondary text-decoration-none">Simulasi Estimasi Biaya</a></li>
          <li><a href="{{ url('/customer/tracking') }}" class="text-secondary text-decoration-none">Live Tracking Pesanan</a></li>
          <li><a href="{{ url('/partner/dashboard') }}" class="text-secondary text-decoration-none text-success fw-medium"><i class="bi bi-arrow-right-short"></i> Portal Mitra Teknisi</a></li>
          <li><a href="{{ url('/admin/dashboard') }}" class="text-secondary text-decoration-none text-primary fw-medium"><i class="bi bi-arrow-right-short"></i> Portal Admin Sistem</a></li>
          <li><a href="{{ url('/partner/login') }}" class="text-secondary text-decoration-none">Daftar Jadi Mitra Teknisi</a></li>
        </ul>
      </div>

      <!-- Col 4: Kontak & Jam Layanan -->
      <div class="col-lg-3 col-md-6">
        <h6 class="fw-bold text-dark mb-3">Pusat Bantuan & Kontak</h6>
        <div class="d-flex flex-column gap-2 text-secondary" style="font-size: 0.9rem;">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-whatsapp text-success fs-5"></i>
            <span>+62 857-1431-0516 (Iqbal Aditya)</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-envelope text-primary fs-5"></i>
            <span>bantuan@betulinaja.id</span>
          </div>
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-geo-alt text-danger fs-5"></i>
            <span>Gedung Graha Betulin, Jl. Sudirman Kav. 21, Jakarta</span>
          </div>
          <div class="d-flex align-items-center gap-2 mt-1">
            <i class="bi bi-clock text-secondary fs-5"></i>
            <span>Layanan Siaga: 07.00 - 21.00 WIB (Setiap Hari)</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Copyright & Team Credits -->
    <div class="pt-4 border-top d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3 text-secondary" style="font-size: 0.85rem;">
      <div>
        &copy; {{ date('Y') }} <strong>BetulinAja Inc.</strong> &bull; Karya Mahasiswa <strong>Universitas Insan Pembangunan Indonesia (UNIPI)</strong>.
      </div>
      <div class="text-center text-lg-end text-muted">
        <span>Tim Pengembang: </span>
        <strong class="text-dark">Iqbal Adityajaya</strong> (Pembuat & Pengembang), 
        <strong class="text-dark">Maulana Rizki</strong> (Penemu), 
        <strong class="text-dark">Sapriadi Sandy</strong> (CEO)
      </div>
    </div>
  </div>
</footer>
