@extends('layouts.partner')

@section('title', 'Detail Pesanan #ORD-849201 — Mitra BetulinAja')
@section('page_title', 'Detail Pekerjaan & Informasi Pelanggan')

@section('partner_content')
<div class="row g-4">
  <div class="col-lg-8">
    <!-- Main Order Info -->
    <div class="card card-custom p-4 mb-4">
      <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 pb-3 border-bottom mb-4">
        <div>
          <span class="text-secondary small d-block">Nomor Pesanan</span>
          <h4 class="fw-bold font-monospace text-primary mb-0">#ORD-849201</h4>
        </div>
        <span class="badge-soft-primary px-3 py-2 fw-semibold">Dalam Penugasan Anda</span>
      </div>

      <h5 class="fw-bold text-dark mb-3">Informasi Layanan & Kendala</h5>
      <div class="bg-light rounded-3 p-3 mb-4">
        <div class="row g-3 small">
          <div class="col-sm-6">
            <span class="text-secondary d-block">Kategori</span>
            <strong class="text-dark">Service AC & Pendingin</strong>
          </div>
          <div class="col-sm-6">
            <span class="text-secondary d-block">Jenis Pekerjaan</span>
            <strong class="text-dark">Cuci AC Standar (0.5 - 1 PK)</strong>
          </div>
          <div class="col-12 border-top pt-2">
            <span class="text-secondary d-block">Keluhan Pelanggan</span>
            <p class="text-dark mb-0 fst-italic">"AC kamar indoor meneteskan air ke lantai dan hembusan angin terasa kurang dingin. Mohon dicek juga apakah freonnya habis."</p>
          </div>
        </div>
      </div>

      <!-- Foto Kerusakan dari Pelanggan -->
      <h6 class="fw-bold text-dark mb-2">Foto Kondisi dari Pelanggan</h6>
      <div class="d-flex gap-3 mb-4 overflow-x-auto pb-2">
        <div class="rounded-3 border overflow-hidden position-relative" style="width: 140px; height: 110px; flex-shrink: 0;">
          <img src="https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=300&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Indoor AC">
        </div>
        <div class="rounded-3 border overflow-hidden position-relative" style="width: 140px; height: 110px; flex-shrink: 0;">
          <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=300&auto=format&fit=crop&q=80" class="w-100 h-100 object-fit-cover" alt="Outdoor Unit">
        </div>
      </div>

      <div class="d-flex flex-wrap gap-2">
        <a href="{{ url('/partner/active-job?order=ORD-849201') }}" class="btn btn-primary-custom px-4">
          <i class="bi bi-play-circle me-1"></i> Mulai Lembar Pekerjaan
        </a>
        <a href="{{ url('/partner/orders') }}" class="btn btn-outline-custom">
          Kembali ke Daftar Order
        </a>
      </div>
    </div>
  </div>

  <!-- Customer Contact Card -->
  <div class="col-lg-4">
    <div class="card card-custom p-4 mb-4">
      <h5 class="fw-bold text-dark mb-3">Data Pemesan</h5>
      
      <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
        <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&auto=format&fit=crop&q=80" class="rounded-circle" style="width: 52px; height: 52px;" alt="Ahmad">
        <div>
          <h6 class="fw-bold text-dark mb-0">Ahmad Fauzi</h6>
          <span class="badge-soft-success small py-0 px-2">Pelanggan Terverifikasi</span>
        </div>
      </div>

      <div class="mb-3">
        <span class="text-muted small d-block">Alamat Lokasi:</span>
        <p class="text-dark small mb-1 fw-semibold">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</p>
        <span class="text-secondary small fst-italic"><i class="bi bi-pin-map text-danger me-1"></i>Patokan: Pagar warna hitam, samping minimarket Alfamart</span>
      </div>

      <div class="d-grid gap-2 pt-3 border-top">
        <button type="button" class="btn btn-outline-custom w-100" onclick="BetulinAja.showToast('Peta Rute', 'Membuka Google Maps navigasi rute tercepat...', 'info')">
          <i class="bi bi-compass text-primary me-2"></i> Buka Rute Navigasi GPS
        </button>
        <button type="button" class="btn btn-outline-custom w-100" onclick="BetulinAja.showToast('Telepon Pelanggan', 'Menghubungi Ahmad Fauzi (0812-9876-xxxx)...', 'success')">
          <i class="bi bi-telephone-fill text-success me-2"></i> Hubungi WhatsApp / Telp
        </button>
      </div>
    </div>

    <!-- Payout Breakdown -->
    <div class="card card-custom p-4">
      <h6 class="fw-bold text-dark mb-3">Estimasi Komisi Mitra</h6>
      <div class="d-flex justify-content-between py-1 small">
        <span class="text-secondary">Total Tagihan Jasa</span>
        <span class="text-dark fw-semibold">Rp150.000</span>
      </div>
      <div class="d-flex justify-content-between py-1 small">
        <span class="text-secondary">Biaya Admin Platform (15%)</span>
        <span class="text-danger">-Rp22.500</span>
      </div>
      <div class="d-flex justify-content-between pt-2 border-top">
        <span class="fw-bold text-dark">Pendapatan Bersih Anda</span>
        <span class="fw-bold text-success fs-5">Rp127.500</span>
      </div>
    </div>
  </div>
</div>
@endsection
