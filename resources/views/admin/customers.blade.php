@extends('layouts.admin')

@section('title', 'Manajemen Pelanggan — Admin BetulinAja')
@section('page_title', 'Daftar Pengguna / Pelanggan')

@section('admin_content')
<div class="admin-table-wrapper p-4 mb-4">
  <div class="row g-3 align-items-center mb-4">
    <div class="col-md-6">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control border-start-0" placeholder="Cari nama pelanggan, nomor WhatsApp, atau email...">
      </div>
    </div>
    <div class="col-md-6 text-md-end">
      <span class="text-secondary small">Total 2.840 Pelanggan Terdaftar</span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table admin-table align-middle">
      <thead>
        <tr>
          <th>Pelanggan</th>
          <th>Kontak</th>
          <th>Alamat Domisili</th>
          <th>Total Order</th>
          <th>Total Belanja</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100&auto=format&fit=crop&q=80" class="rounded-circle shadow-sm" style="width: 38px; height: 38px;" alt="Ahmad">
              <div>
                <span class="fw-bold text-dark d-block">Ahmad Fauzi</span>
                <small class="text-muted">Member sejak Jan 2025</small>
              </div>
            </div>
          </td>
          <td>
            <span class="d-block text-dark small">0812-9876-5432</span>
            <small class="text-secondary">ahmad.fauzi@example.com</small>
          </td>
          <td class="small text-secondary">Kebayoran Baru, Jakarta Selatan</td>
          <td><span class="fw-bold text-dark">4 Pesanan</span></td>
          <td class="fw-bold text-primary">Rp565.000</td>
          <td><span class="badge-soft-success small">Aktif</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Data Pelanggan', 'Membuka rekapitulasi pesanan Ahmad Fauzi', 'info')">
              Profil
            </button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" class="rounded-circle shadow-sm" style="width: 38px; height: 38px;" alt="Dewi">
              <div>
                <span class="fw-bold text-dark d-block">Dewi Kartika</span>
                <small class="text-muted">Member sejak Mar 2025</small>
              </div>
            </div>
          </td>
          <td>
            <span class="d-block text-dark small">0813-9988-7711</span>
            <small class="text-secondary">dewi.kartika@example.com</small>
          </td>
          <td class="small text-secondary">Senopati, Jakarta Selatan</td>
          <td><span class="fw-bold text-dark">6 Pesanan</span></td>
          <td class="fw-bold text-primary">Rp920.000</td>
          <td><span class="badge-soft-success small">Aktif</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Data Pelanggan', 'Membuka rekapitulasi pesanan Dewi Kartika', 'info')">
              Profil
            </button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=100&auto=format&fit=crop&q=80" class="rounded-circle shadow-sm" style="width: 38px; height: 38px;" alt="Bambang">
              <div>
                <span class="fw-bold text-dark d-block">Bambang Setiawan</span>
                <small class="text-muted">Member sejak Mei 2025</small>
              </div>
            </div>
          </td>
          <td>
            <span class="d-block text-dark small">0817-2233-4455</span>
            <small class="text-secondary">bambang.s@example.com</small>
          </td>
          <td class="small text-secondary">Cilandak, Jakarta Selatan</td>
          <td><span class="fw-bold text-dark">2 Pesanan</span></td>
          <td class="fw-bold text-primary">Rp295.000</td>
          <td><span class="badge-soft-success small">Aktif</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Data Pelanggan', 'Membuka rekapitulasi pesanan Bambang Setiawan', 'info')">
              Profil
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
