@extends('layouts.partner')

@section('title', 'Lembar Kerja Aktif #ORD-849201 — Mitra BetulinAja')
@section('page_title', 'Pekerjaan Berjalan: #ORD-849201')

@section('partner_content')
<div class="row g-4 justify-content-center">
  <div class="col-lg-9">
    <!-- Live Status Banner -->
    <div class="card card-custom p-4 mb-4">
      <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 pb-3 border-bottom mb-3">
        <div>
          <span class="text-muted small d-block">Status Pekerjaan Terkini</span>
          <div class="d-flex align-items-center gap-2 mt-1">
            <span class="badge-soft-primary px-3 py-2 fw-bold" id="active-job-status-badge">
              <i class="bi bi-bicycle me-1"></i> Dalam Perjalanan ke Lokasi
            </span>
            <span class="text-secondary small">&bull; Dimulai 14:10 WIB</span>
          </div>
        </div>
        <div class="text-sm-end">
          <span class="text-muted small d-block">Pelanggan</span>
          <span class="fw-bold text-dark">Ahmad Fauzi &bull; 0812-9876-xxxx</span>
        </div>
      </div>

      <!-- Progressive Workflow Action Buttons -->
      <div class="p-3 bg-light rounded-3 text-center mb-2">
        <h6 class="fw-bold text-dark mb-1">Aksi Progres Pekerjaan:</h6>
        <p class="text-secondary small mb-3">Tekan tombol aksi berikut saat Anda berganti status di lapangan untuk mengupdate status pelanggan.</p>

        <div class="d-flex flex-wrap justify-content-center gap-2" id="job-flow-buttons">
          <!-- Step 1 Button (Active initially) -->
          <button type="button" class="btn btn-outline-custom px-4 py-2 d-none" id="btn-start-travel">
            <i class="bi bi-bicycle me-2"></i> Mulai Perjalanan
          </button>

          <!-- Step 2 Button -->
          <button type="button" class="btn btn-primary-custom px-4 py-2" id="btn-arrived-location">
            <i class="bi bi-geo-alt-fill me-2"></i> Konfirmasi Tiba di Lokasi
          </button>

          <!-- Step 3 Button -->
          <button type="button" class="btn btn-warning px-4 py-2 fw-semibold d-none" id="btn-start-job">
            <i class="bi bi-tools me-2"></i> Mulai Pengerjaan Service
          </button>

          <!-- Step 4 Button (Complete) -->
          <button type="button" class="btn btn-accent-green px-4 py-2 d-none" id="btn-complete-job">
            <i class="bi bi-check-circle-fill me-2"></i> Selesaikan Pekerjaan
          </button>
        </div>
      </div>
    </div>

    <!-- Proof Photos Upload Section (Before & After) -->
    <div class="card card-custom p-4 mb-4" id="job-proof-section">
      <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
        <div>
          <h5 class="fw-bold text-dark mb-0">Dokumentasi Bukti Kerja (Sebelum & Sesudah)</h5>
          <span class="text-secondary small">Wajib mengunggah foto sebelum dan sesudah untuk klaim garansi pelanggan.</span>
        </div>
        <span class="badge-soft-primary small">SOP BetulinAja</span>
      </div>

      <div class="row g-4">
        <!-- Foto Sebelum -->
        <div class="col-md-6">
          <div class="border rounded-3 p-3 bg-light text-center">
            <h6 class="fw-bold text-dark mb-2">1. Foto Sebelum Pengerjaan</h6>
            <div id="preview-before-photo" class="mb-2 d-flex align-items-center justify-content-center bg-white rounded-3 border" style="height: 140px;">
              <span class="text-muted small fst-italic"><i class="bi bi-camera me-1"></i>Belum ada foto</span>
            </div>
            <label class="btn btn-outline-custom btn-sm w-100 cursor-pointer">
              <i class="bi bi-upload me-1"></i> Ambil / Upload Foto Sebelum
              <input type="file" id="upload-before-photo" accept="image/*" class="d-none">
            </label>
          </div>
        </div>

        <!-- Foto Sesudah -->
        <div class="col-md-6">
          <div class="border rounded-3 p-3 bg-light text-center">
            <h6 class="fw-bold text-dark mb-2">2. Foto Sesudah Selesai</h6>
            <div id="preview-after-photo" class="mb-2 d-flex align-items-center justify-content-center bg-white rounded-3 border" style="height: 140px;">
              <span class="text-muted small fst-italic"><i class="bi bi-camera me-1"></i>Belum ada foto</span>
            </div>
            <label class="btn btn-outline-custom btn-sm w-100 cursor-pointer">
              <i class="bi bi-upload me-1"></i> Ambil / Upload Foto Sesudah
              <input type="file" id="upload-after-photo" accept="image/*" class="d-none">
            </label>
          </div>
        </div>
      </div>
    </div>

    <!-- Job Specifications & Address Summary -->
    <div class="card card-custom p-4">
      <h6 class="fw-bold text-dark mb-3">Informasi Pesanan Lengkap</h6>
      <div class="row g-3 small">
        <div class="col-sm-6">
          <span class="text-muted d-block">Jenis Layanan</span>
          <span class="fw-bold text-dark">Cuci AC Standar (0.5 - 1 PK)</span>
        </div>
        <div class="col-sm-6">
          <span class="text-muted d-block">Total Tagihan Pelanggan</span>
          <span class="fw-bold text-primary">Rp150.000 (Tunai / QRIS)</span>
        </div>
        <div class="col-12 border-top pt-2">
          <span class="text-muted d-block">Alamat Pelanggan</span>
          <span class="text-dark">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan (Pagar hitam)</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ==========================================
     MODAL SELESAI PEKERJAAN
     ========================================== -->
<div class="modal fade" id="jobCompletionModal" tabindex="-1" aria-labelledby="jobCompletionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-custom border-0 p-4 text-center">
      <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mx-auto mb-3 shadow" style="width: 70px; height: 70px;">
        <i class="bi bi-check-lg display-6"></i>
      </div>
      <h4 class="fw-bold text-dark mb-1" id="jobCompletionModalLabel">Pekerjaan Berhasil Diselesaikan!</h4>
      <p class="text-secondary small mb-3">
        Terima kasih atas kerja keras Anda. Pelanggan telah menerima notifikasi penyelesaian.
      </p>

      <div class="bg-light rounded-3 p-3 text-start small mb-4">
        <div class="d-flex justify-content-between py-1 border-bottom">
          <span class="text-secondary">Nomor Order</span>
          <span class="fw-bold text-dark font-monospace">#ORD-849201</span>
        </div>
        <div class="d-flex justify-content-between py-1 border-bottom">
          <span class="text-secondary">Penghasilan Bersih</span>
          <span class="fw-bold text-success fs-6">Rp127.500</span>
        </div>
        <div class="d-flex justify-content-between pt-1">
          <span class="text-secondary">Status Saldo</span>
          <span class="badge-soft-success small">Ditambahkan ke Dompet Mitra</span>
        </div>
      </div>

      <div class="d-grid gap-2">
        <a href="{{ url('/partner/earnings') }}" class="btn btn-primary-custom py-2">
          <i class="bi bi-wallet2 me-1"></i> Lihat Saldo Dompet
        </a>
        <a href="{{ url('/partner/dashboard') }}" class="btn btn-outline-custom py-2">
          Kembali ke Beranda Mitra
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
