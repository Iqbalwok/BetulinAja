@extends('layouts.partner')

@section('title', 'Daftar Pesanan Masuk — Mitra BetulinAja')
@section('page_title', 'Manajemen Pesanan Mitra')

@section('partner_content')
<div class="card card-custom p-4 mb-4">
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Daftar Order Tersedia & Riwayat</h5>
      <span class="text-secondary small">Terima order terdekat dari lokasi Anda saat ini.</span>
    </div>
    <div class="d-flex gap-2">
      <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Refresh Data', 'Memperbarui daftar order sekitar...', 'info')">
        <i class="bi bi-arrow-clockwise me-1"></i> Refresh Order
      </button>
    </div>
  </div>

  <!-- Orders Grid / Cards -->
  <div class="row g-3">
    <!-- Order Card 1 -->
    <div class="col-12 order-card-item">
      <div class="border rounded-3 p-3 p-md-4 bg-white hover-shadow transition">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-3 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <span class="badge-soft-danger">Pesanan Baru</span>
            <span class="fw-bold font-monospace text-dark fs-6">#ORD-892134</span>
            <span class="text-secondary small">&bull; Masuk 15 menit lalu</span>
          </div>
          <span class="fw-bold text-success fs-5">Est. Penghasilan: Rp150.000</span>
        </div>

        <div class="row g-3 py-3 align-items-center">
          <div class="col-md-5">
            <h6 class="fw-bold text-dark mb-1">Tambah Freon R32 & Cuci AC 1 PK</h6>
            <p class="text-secondary small mb-1">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i>Jl. Senopati No. 12, RT 01/RW 03, Kebayoran Baru
            </p>
            <span class="badge-soft-secondary small">Jarak: 1.8 km dari Anda</span>
          </div>

          <div class="col-md-4">
            <span class="text-muted small d-block">Catatan Kerusakan dari Pelanggan:</span>
            <p class="text-dark small mb-0 fst-italic">"AC kamar utama hanya keluar angin biasa tidak dingin, ada bau apek."</p>
          </div>

          <div class="col-md-3 text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
              <a href="{{ url('/partner/order-detail?order=ORD-892134') }}" class="btn btn-outline-custom btn-sm">
                Detail
              </a>
              <button type="button" class="btn btn-outline-danger btn-sm btn-reject-order" data-id="ORD-892134">
                Tolak
              </button>
              <button type="button" class="btn btn-accent-green btn-sm px-3 btn-accept-order" data-id="ORD-892134">
                <i class="bi bi-check-lg me-1"></i> Terima
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Card 2 -->
    <div class="col-12 order-card-item">
      <div class="border rounded-3 p-3 p-md-4 bg-white hover-shadow transition">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-3 border-bottom">
          <div class="d-flex align-items-center gap-2">
            <span class="badge-soft-info">Dijadwalkan Nanti</span>
            <span class="fw-bold font-monospace text-dark fs-6">#ORD-892188</span>
            <span class="text-secondary small">&bull; Hari ini, Pukul 16:00 WIB</span>
          </div>
          <span class="fw-bold text-success fs-5">Est. Penghasilan: Rp175.000</span>
        </div>

        <div class="row g-3 py-3 align-items-center">
          <div class="col-md-5">
            <h6 class="fw-bold text-dark mb-1">Perbaikan AC Bocor Menetes Indoor</h6>
            <p class="text-secondary small mb-1">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i>Jl. Wijaya II No. 8, Melawai, Kebayoran Baru
            </p>
            <span class="badge-soft-secondary small">Jarak: 2.9 km dari Anda</span>
          </div>

          <div class="col-md-4">
            <span class="text-muted small d-block">Catatan Kerusakan dari Pelanggan:</span>
            <p class="text-dark small mb-0 fst-italic">"Air menetes deras dari casing indoor membasahi kasur saat dinyalakan."</p>
          </div>

          <div class="col-md-3 text-md-end">
            <div class="d-flex flex-wrap gap-2 justify-content-md-end">
              <a href="{{ url('/partner/order-detail?order=ORD-892188') }}" class="btn btn-outline-custom btn-sm">
                Detail
              </a>
              <button type="button" class="btn btn-outline-danger btn-sm btn-reject-order" data-id="ORD-892188">
                Tolak
              </button>
              <button type="button" class="btn btn-accent-green btn-sm px-3 btn-accept-order" data-id="ORD-892188">
                <i class="bi bi-check-lg me-1"></i> Terima
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
