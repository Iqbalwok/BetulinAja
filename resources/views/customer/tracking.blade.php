@extends('layouts.customer')

@section('title', 'Live Tracking Pesanan — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-2 small">
            <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tracking Pesanan</li>
          </ol>
        </nav>
        <div class="d-flex align-items-center gap-2">
          <h3 class="fw-bold text-dark mb-0">Pesanan <span class="font-monospace text-primary">#ORD-849201</span></h3>
          <span class="badge-soft-primary px-3 py-1 fw-bold" id="tracking-status-badge">Dalam Perjalanan</span>
        </div>
      </div>
      
      <!-- Demo Simulation Controller -->
      <button type="button" class="btn btn-outline-custom btn-sm" id="btn-simulate-status">
        <i class="bi bi-play-circle text-primary me-1"></i> Simulasikan Status Berikutnya
      </button>
    </div>
  </div>
</div>

<div class="container py-4 py-md-5">
  <div class="row g-4">
    <!-- Left Column: Map & Technician Info -->
    <div class="col-lg-8">
      <!-- Live Map Card -->
      <div class="card card-custom p-0 mb-4 overflow-hidden shadow-sm">
        <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-2">
            <span class="status-dot online"></span>
            <span class="fw-bold text-dark small" id="tracking-status-desc">Teknisi sedang menuju lokasi Anda (Est. 12 menit)</span>
          </div>
          <span class="text-muted small"><i class="bi bi-speedometer me-1"></i>Radius 2.4 km</span>
        </div>
        <div id="tracking-leaflet-map" style="height: 380px; width: 100%;"></div>
      </div>

      <!-- Technician Profile Card -->
      <div class="card card-custom p-4 mb-4">
        <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3">
          <div class="d-flex align-items-center gap-3">
            <div class="position-relative">
              <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=150&auto=format&fit=crop&q=80" alt="Budi Santoso" class="rounded-circle object-fit-cover shadow-sm" style="width: 64px; height: 64px; border: 2px solid var(--border-color);">
              <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 14px; height: 14px;"></span>
            </div>
            <div>
              <div class="d-flex align-items-center gap-1">
                <h5 class="fw-bold text-dark mb-0">Budi Santoso</h5>
                <i class="bi bi-patch-check-fill text-primary" title="Mitra Terverifikasi"></i>
              </div>
              <span class="text-secondary small d-block">Teknisi AC & Pendingin Berpengalaman</span>
              <div class="d-flex align-items-center gap-2 mt-1" style="font-size: 0.85rem;">
                <span class="text-warning fw-bold"><i class="bi bi-star-fill"></i> 4.9</span>
                <span class="text-muted">(142 Ulasan &bull; 284 Order Selesai)</span>
              </div>
            </div>
          </div>

          <!-- Contact Buttons -->
          <div class="d-flex gap-2 w-100 w-sm-auto">
            <button type="button" class="btn btn-outline-custom flex-fill flex-sm-grow-0" data-bs-toggle="modal" data-bs-target="#chatModal">
              <i class="bi bi-chat-dots text-primary me-1"></i> Chat
            </button>
            <button type="button" class="btn btn-primary-custom flex-fill flex-sm-grow-0" data-bs-toggle="modal" data-bs-target="#phoneCallModal" id="btn-start-call">
              <i class="bi bi-telephone-fill me-1"></i> Telepon
            </button>
          </div>
        </div>
      </div>

      <!-- Service Details Breakdown -->
      <div class="card card-custom p-4">
        <h5 class="fw-bold text-dark mb-3">Rincian Pekerjaan & Biaya</h5>
        <div class="table-responsive">
          <table class="table table-borderless small mb-0">
            <tbody>
              <tr class="border-bottom">
                <td class="text-secondary ps-0">Layanan</td>
                <td class="text-end fw-semibold text-dark pe-0">Cuci AC Standar (0.5 - 1 PK)</td>
              </tr>
              <tr class="border-bottom">
                <td class="text-secondary ps-0">Alamat Pengerjaan</td>
                <td class="text-end text-dark pe-0">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</td>
              </tr>
              <tr class="border-bottom">
                <td class="text-secondary ps-0">Biaya Dasar Jasa</td>
                <td class="text-end text-dark pe-0">Rp100.000</td>
              </tr>
              <tr class="border-bottom">
                <td class="text-secondary ps-0">Biaya Transport (5 km)</td>
                <td class="text-end text-dark pe-0">Rp25.000</td>
              </tr>
              <tr class="border-bottom">
                <td class="text-secondary ps-0">Biaya Kesulitan (Sedang)</td>
                <td class="text-end text-dark pe-0">Rp25.000</td>
              </tr>
              <tr>
                <td class="fw-bold text-dark fs-6 ps-0 pt-3">Estimasi Total</td>
                <td class="text-end fw-bold text-primary fs-5 pe-0 pt-3">Rp150.000</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Right Column: Process Timeline -->
    <div class="col-lg-4">
      <div class="card card-custom p-4 sticky-lg-top" style="top: 100px;">
        <h5 class="fw-bold text-dark mb-4">Status & Timeline Proses</h5>

        <div class="tracking-timeline">
          <!-- 1. Menunggu Konfirmasi -->
          <div class="timeline-item completed">
            <div class="timeline-dot"><i class="bi bi-check"></i></div>
            <h6 class="fw-bold text-dark mb-0 small">Menunggu Konfirmasi</h6>
            <span class="text-muted" style="font-size: 0.75rem;">14:00 WIB &bull; Pesanan dibuat</span>
          </div>

          <!-- 2. Mencari Teknisi -->
          <div class="timeline-item completed">
            <div class="timeline-dot"><i class="bi bi-check"></i></div>
            <h6 class="fw-bold text-dark mb-0 small">Mencari Teknisi</h6>
            <span class="text-muted" style="font-size: 0.75rem;">14:02 WIB &bull; Sistem mencari mitra</span>
          </div>

          <!-- 3. Teknisi Ditemukan -->
          <div class="timeline-item completed">
            <div class="timeline-dot"><i class="bi bi-check"></i></div>
            <h6 class="fw-bold text-dark mb-0 small">Teknisi Ditemukan</h6>
            <span class="text-muted" style="font-size: 0.75rem;">14:05 WIB &bull; Budi Santoso menerima</span>
          </div>

          <!-- 4. Dalam Perjalanan -->
          <div class="timeline-item current">
            <div class="timeline-dot"><i class="bi bi-bicycle"></i></div>
            <h6 class="fw-bold text-primary mb-0 small">Dalam Perjalanan</h6>
            <span class="text-secondary" style="font-size: 0.75rem;">14:10 WIB &bull; Menuju lokasi Anda</span>
          </div>

          <!-- 5. Sedang Dikerjakan -->
          <div class="timeline-item">
            <div class="timeline-dot"><i class="bi bi-circle"></i></div>
            <h6 class="fw-bold text-secondary mb-0 small">Sedang Dikerjakan</h6>
            <span class="text-muted" style="font-size: 0.75rem;">Menunggu teknisi tiba</span>
          </div>

          <!-- 6. Selesai -->
          <div class="timeline-item">
            <div class="timeline-dot"><i class="bi bi-circle"></i></div>
            <h6 class="fw-bold text-secondary mb-0 small">Selesai</h6>
            <span class="text-muted" style="font-size: 0.75rem;">Pekerjaan rampung & bergaransi</span>
          </div>
        </div>

        <div class="pt-4 border-top mt-4">
          <div class="d-flex align-items-center gap-2 text-secondary small">
            <i class="bi bi-shield-check text-success fs-5"></i>
            <span>Dilindungi <strong>Garansi 30 Hari</strong> BetulinAja</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     MODAL INTERAKTIF: CHAT DENGAN TEKNISI
     ========================================== -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-custom border-0">
      <div class="modal-header border-bottom p-3">
        <div class="d-flex align-items-center gap-2">
          <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=100&auto=format&fit=crop&q=80" class="rounded-circle" style="width: 36px; height: 36px;" alt="Budi">
          <div>
            <h6 class="fw-bold text-dark mb-0 small" id="chatModalLabel">Budi Santoso (Teknisi)</h6>
            <span class="text-success" style="font-size: 0.72rem;"><span class="status-dot online me-1"></span>Online</span>
          </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-3">
        <div class="chat-messages" id="chat-messages-container">
          <div class="chat-bubble technician">
            <div>Halo Pak/Bu, saya Budi teknisi AC BetulinAja. Saya sedang dalam perjalanan menuju rumah Anda. Mohon konfirmasi apakah pagar rumah warna hitam?</div>
            <span class="d-block text-muted text-end" style="font-size: 0.7rem;">14:12</span>
          </div>
        </div>
      </div>
      <div class="modal-footer border-top p-2">
        <div class="input-group">
          <input type="text" id="chat-input-message" class="form-control border-end-0" placeholder="Ketik pesan untuk teknisi...">
          <button class="btn btn-primary-custom" type="button" id="btn-send-chat">
            <i class="bi bi-send-fill"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     MODAL INTERAKTIF: PANGGILAN TELEPON DEMO
     ========================================== -->
<div class="modal fade" id="phoneCallModal" tabindex="-1" aria-labelledby="phoneCallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content card-custom border-0 text-center p-4">
      <div class="mb-3">
        <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=150&auto=format&fit=crop&q=80" class="rounded-circle mx-auto shadow" style="width: 80px; height: 80px; border: 3px solid #EFF6FF;" alt="Budi">
      </div>
      <h5 class="fw-bold text-dark mb-1">Budi Santoso</h5>
      <span class="text-secondary small mb-3 d-block">Panggilan Suara VoIP BetulinAja</span>
      <div class="badge-soft-primary mx-auto py-1 px-3 mb-4 small" id="call-status-text">Memanggil Teknisi...</div>
      <button type="button" class="btn btn-danger btn-lg rounded-circle mx-auto p-3 shadow" style="width: 60px; height: 60px;" data-bs-dismiss="modal">
        <i class="bi bi-telephone-x-fill fs-5"></i>
      </button>
    </div>
  </div>
</div>
@endsection
