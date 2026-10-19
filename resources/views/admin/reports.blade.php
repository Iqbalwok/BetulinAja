@extends('layouts.admin')

@section('title', 'Laporan & Analitik — Admin BetulinAja')
@section('page_title', 'Laporan Kinerja Transaksi & Finansial')

@section('admin_content')
<div class="row g-4 mb-4">
  <!-- Report Summary Cards -->
  <div class="col-md-4">
    <div class="card card-custom p-4 h-100">
      <span class="text-secondary small fw-bold text-uppercase mb-1">Gross Merchandise Volume (GMV)</span>
      <h3 class="fw-bold text-dark mb-2">Rp224.500.000</h3>
      <span class="text-success small fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+22.5% dibanding Q2</span>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-custom p-4 h-100">
      <span class="text-secondary small fw-bold text-uppercase mb-1">Komisi Platform (Net Revenue)</span>
      <h3 class="fw-bold text-primary mb-2">Rp33.675.000</h3>
      <span class="text-muted small">Tarif potongan 15% dari total pesanan</span>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-custom p-4 h-100">
      <span class="text-secondary small fw-bold text-uppercase mb-1">Total Jam Pengerjaan</span>
      <h3 class="fw-bold text-dark mb-2">3.120 Jam</h3>
      <span class="text-secondary small">Rata-rata 1.8 jam per pekerjaan</span>
    </div>
  </div>
</div>

<!-- Export Options Card -->
<div class="card card-custom p-4 mb-4">
  <h5 class="fw-bold text-dark mb-3">Unduh Laporan Periode</h5>
  <div class="row g-3 align-items-center">
    <div class="col-sm-4">
      <label class="form-label small">Pilih Jenis Laporan</label>
      <select class="form-select">
        <option>Rekapitulasi Transaksi Lengkap</option>
        <option>Laporan Pembagian Komisi Mitra</option>
        <option>Statistik Kepuasan & Ulasan Pelanggan</option>
      </select>
    </div>
    <div class="col-sm-4">
      <label class="form-label small">Periode Waktu</label>
      <select class="form-select">
        <option>Bulan Berjalan (Oktober 2026)</option>
        <option>Kuartal 3 (Jul - Sep 2026)</option>
        <option>Tahun Penuh 2026 (YTD)</option>
      </select>
    </div>
    <div class="col-sm-4 pt-sm-4">
      <button type="button" class="btn btn-primary-custom w-100" onclick="BetulinAja.showToast('Ekspor Laporan', 'File laporan Excel/PDF sedang disiapkan untuk diunduh (Mode Demo).', 'success')">
        <i class="bi bi-file-earmark-excel me-1"></i> Unduh File Laporan
      </button>
    </div>
  </div>
</div>
@endsection
