@extends('layouts.admin')

@section('title', 'Admin Dashboard — BetulinAja')
@section('page_title', 'Ringkasan Eksekutif & Statistik Marketplace')

@section('admin_content')
<!-- Metric Stats Grid (4 Cards) -->
<div class="row g-3 g-md-4 mb-4">
  <!-- Metric 1: Total Pesanan -->
  <div class="col-6 col-lg-3">
    <div class="admin-stat-card">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Total Pesanan</span>
        <div class="admin-stat-icon bg-primary text-white">
          <i class="bi bi-receipt"></i>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-1">1.482</h2>
        <span class="text-success small fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+14.2% bulan ini</span>
      </div>
    </div>
  </div>

  <!-- Metric 2: Pesanan Selesai -->
  <div class="col-6 col-lg-3">
    <div class="admin-stat-card">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Pesanan Selesai</span>
        <div class="admin-stat-icon bg-success text-white">
          <i class="bi bi-check-circle"></i>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-1">1.390</h2>
        <span class="text-secondary small fw-medium">Tingkat Berhasil 93.8%</span>
      </div>
    </div>
  </div>

  <!-- Metric 3: Pesanan Dalam Proses -->
  <div class="col-6 col-lg-3">
    <div class="admin-stat-card">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Pesanan Aktif</span>
        <div class="admin-stat-icon bg-warning text-dark">
          <i class="bi bi-hourglass-split"></i>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-1">48</h2>
        <span class="text-primary small fw-medium">Sedang Ditangani Mitra</span>
      </div>
    </div>
  </div>

  <!-- Metric 4: Teknisi Aktif -->
  <div class="col-6 col-lg-3">
    <div class="admin-stat-card">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="text-secondary small fw-bold text-uppercase" style="letter-spacing: 0.05em;">Mitra Online</span>
        <div class="admin-stat-icon bg-info text-white">
          <i class="bi bi-person-check"></i>
        </div>
      </div>
      <div>
        <h2 class="fw-bold text-dark mb-1">324</h2>
        <span class="text-success small fw-medium"><span class="status-dot online me-1"></span>Siaga di Lapangan</span>
      </div>
    </div>
  </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-4">
  <!-- Orders Trend Line Chart -->
  <div class="col-lg-8">
    <div class="card card-custom p-4 h-100">
      <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-2 mb-3">
        <div>
          <h5 class="fw-bold text-dark mb-0">Tren Pesanan 7 Hari Terakhir</h5>
          <span class="text-secondary small">Perbandingan volume pesanan sukses dan dibatalkan.</span>
        </div>
        <span class="badge-soft-primary small py-1 px-3">Minggu Berjalan</span>
      </div>
      <div style="height: 290px; position: relative;">
        <canvas id="adminOrdersChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Service Category Distribution Chart -->
  <div class="col-lg-4">
    <div class="card card-custom p-4 h-100">
      <h5 class="fw-bold text-dark mb-1">Pangsa Pasar Layanan</h5>
      <span class="text-secondary small mb-3 d-block">Distribusi kategori perbaikan terpopuler.</span>
      <div style="height: 250px; position: relative;">
        <canvas id="adminCategoryChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders & Activity Summary -->
<div class="row g-4">
  <!-- Recent Orders Table -->
  <div class="col-lg-8">
    <div class="admin-table-wrapper">
      <div class="p-4 border-bottom d-flex align-items-center justify-content-between">
        <div>
          <h5 class="fw-bold text-dark mb-0">Pesanan Masuk Terbaru</h5>
          <span class="text-secondary small">Daftar transaksi real-time dari seluruh wilayah.</span>
        </div>
        <a href="{{ url('/admin/orders') }}" class="btn btn-outline-custom btn-sm">
          Lihat Semua Pesanan <i class="bi bi-arrow-right ms-1"></i>
        </a>
      </div>

      <div class="table-responsive">
        <table class="table admin-table mb-0">
          <thead>
            <tr>
              <th>ID Order</th>
              <th>Pelanggan</th>
              <th>Layanan</th>
              <th>Teknisi</th>
              <th>Total</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="font-monospace fw-bold text-primary">#ORD-849201</td>
              <td>Ahmad Fauzi</td>
              <td>Cuci AC Standar</td>
              <td>Budi Santoso</td>
              <td class="fw-bold">Rp150.000</td>
              <td><span class="badge-soft-primary small">Dalam Perjalanan</span></td>
            </tr>
            <tr>
              <td class="font-monospace fw-bold text-primary">#ORD-892134</td>
              <td>Dewi Kartika</td>
              <td>Freon R32 AC</td>
              <td>Menunggu Mitra</td>
              <td class="fw-bold">Rp175.000</td>
              <td><span class="badge-soft-warning small">Mencari Teknisi</span></td>
            </tr>
            <tr>
              <td class="font-monospace fw-bold text-primary">#ORD-781923</td>
              <td>Bambang Setiawan</td>
              <td>Korsleting MCB Listrik</td>
              <td>I Made Agus</td>
              <td class="fw-bold">Rp145.000</td>
              <td><span class="badge-soft-success small">Selesai</span></td>
            </tr>
            <tr>
              <td class="font-monospace fw-bold text-primary">#ORD-629104</td>
              <td>Rina Melati</td>
              <td>Wastafel Pipa Mampet</td>
              <td>Hendro Wijaya</td>
              <td class="fw-bold">Rp120.000</td>
              <td><span class="badge-soft-success small">Selesai</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Live System Activity -->
  <div class="col-lg-4">
    <div class="card card-custom p-4 h-100">
      <h5 class="fw-bold text-dark mb-3">Aktivitas Terkini Platform</h5>

      <div class="d-flex flex-column gap-3 small">
        <div class="d-flex align-items-start gap-2 pb-2 border-bottom">
          <i class="bi bi-check-circle-fill text-success mt-1"></i>
          <div>
            <span class="fw-semibold text-dark">Order #ORD-781923 Selesai</span>
            <p class="text-secondary mb-0">Teknisi I Made Agus merampungkan perbaikan kelistrikan.</p>
            <span class="text-muted" style="font-size: 0.72rem;">2 menit lalu</span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-2 pb-2 border-bottom">
          <i class="bi bi-bicycle text-primary mt-1"></i>
          <div>
            <span class="fw-semibold text-dark">Teknisi Meluncur #ORD-849201</span>
            <p class="text-secondary mb-0">Budi Santoso berangkat ke Kebayoran Baru.</p>
            <span class="text-muted" style="font-size: 0.72rem;">8 menit lalu</span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-2 pb-2 border-bottom">
          <i class="bi bi-person-plus-fill text-info mt-1"></i>
          <div>
            <span class="fw-semibold text-dark">Pendaftaran Mitra Baru</span>
            <p class="text-secondary mb-0">Surya Saputra mengunggah dokumen KTP & SKCK.</p>
            <span class="text-muted" style="font-size: 0.72rem;">25 menit lalu</span>
          </div>
        </div>

        <div class="d-flex align-items-start gap-2">
          <i class="bi bi-star-fill text-warning mt-1"></i>
          <div>
            <span class="fw-semibold text-dark">Ulasan Bintang 5 Masuk</span>
            <p class="text-secondary mb-0">"Pelayanan cepat dan bersih." &bull; Rina Melati</p>
            <span class="text-muted" style="font-size: 0.72rem;">40 menit lalu</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
