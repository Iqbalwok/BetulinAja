@extends('layouts.customer')

@section('title', 'Pesanan Dikonfirmasi — BetulinAja')

@section('customer_content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 text-center">
      <!-- Success Icon -->
      <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle mb-4 shadow" style="width: 84px; height: 84px;">
        <i class="bi bi-check-lg display-5"></i>
      </div>

      <span class="badge-soft-success small mb-2 d-inline-block px-3 py-1">Pemesanan Berhasil</span>
      <h2 class="fw-bold text-dark mb-2">Pesanan Anda Telah Dikonfirmasi!</h2>
      <p class="text-secondary small mb-4">
        Teknisi terdekat telah menerima penugasan dan sedang bersiap menuju lokasi perbaikan Anda.
      </p>

      <!-- Order Receipt Card -->
      <div class="card card-custom p-4 text-start mb-4">
        <div class="d-flex justify-content-between align-items-center pb-3 border-bottom mb-3">
          <div>
            <span class="text-muted small d-block" style="font-size: 0.75rem;">Nomor Pesanan</span>
            <span class="fw-bold text-primary font-monospace fs-5">#ORD-849201</span>
          </div>
          <span class="badge-soft-primary small py-1 px-3">Teknisi Menuju Lokasi</span>
        </div>

        <div class="row g-3 small mb-3">
          <div class="col-6">
            <span class="text-secondary d-block">Layanan</span>
            <strong class="text-dark">Cuci AC Standar (0.5 - 1 PK)</strong>
          </div>
          <div class="col-6 text-end">
            <span class="text-secondary d-block">Teknisi Bertugas</span>
            <strong class="text-dark">Budi Santoso</strong>
          </div>
          <div class="col-12">
            <span class="text-secondary d-block">Alamat Kunjungan</span>
            <span class="text-dark">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</span>
          </div>
          <div class="col-6">
            <span class="text-secondary d-block">Jadwal</span>
            <span class="text-dark">Hari Ini, Langsung (Instan)</span>
          </div>
          <div class="col-6 text-end">
            <span class="text-secondary d-block">Metode Pembayaran</span>
            <span class="text-dark">Tunai / QRIS di Lokasi</span>
          </div>
        </div>

        <div class="pt-3 border-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-dark">Estimasi Total</span>
          <span class="fw-bold text-primary fs-4">Rp150.000</span>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
        <a href="{{ url('/customer/tracking?order=ORD-849201') }}" class="btn btn-primary-custom btn-lg">
          <i class="bi bi-geo-alt me-2"></i> Lacak Teknisi Sekarang
        </a>
        <a href="{{ url('/customer/order-history') }}" class="btn btn-outline-custom btn-lg">
          <i class="bi bi-clock-history me-2"></i> Lihat Semua Pesanan
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
