@extends('layouts.admin')

@section('title', 'Manajemen Mitra Teknisi — Admin BetulinAja')
@section('page_title', 'Kelola & Verifikasi Mitra Teknisi')

@section('admin_content')
<div class="admin-table-wrapper p-4 mb-4">
  <div class="row g-3 align-items-center mb-4">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control border-start-0" placeholder="Cari nama teknisi, nomor HP, atau spesialisasi...">
      </div>
    </div>
    <div class="col-md-4">
      <select class="form-select">
        <option selected>Semua Status Verifikasi</option>
        <option>Terverifikasi Penuh</option>
        <option>Menunggu Review Dokumen</option>
        <option>Ditangguhkan</option>
      </select>
    </div>
    <div class="col-md-3 text-md-end">
      <button type="button" class="btn btn-primary-custom btn-sm w-100 w-md-auto" onclick="BetulinAja.showToast('Tambah Mitra', 'Membuka formulir pendaftaran mitra manual...', 'info')">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah Teknisi
      </button>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table admin-table align-middle">
      <thead>
        <tr>
          <th>Teknisi</th>
          <th>Spesialisasi</th>
          <th>Wilayah Tugas</th>
          <th>Rating / Order</th>
          <th>Status Kemitraan</th>
          <th>Ketersediaan</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" alt="Budi">
              <div>
                <span class="fw-bold text-dark d-block">Budi Santoso</span>
                <small class="text-muted">0812-3456-7890</small>
              </div>
            </div>
          </td>
          <td><span class="badge-soft-primary small">AC & Pendingin</span></td>
          <td>Jakarta Selatan & Pusat</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 4.9</span>
            <small class="text-muted">(284 Order)</small>
          </td>
          <td><span class="badge-soft-success small"><i class="bi bi-patch-check-fill me-1"></i>Terverifikasi</span></td>
          <td><span class="badge-soft-success small"><span class="status-dot online me-1"></span>Online</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Detail Mitra', 'Membuka profil & riwayat order Budi Santoso', 'info')">
              Detail
            </button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" alt="Agus">
              <div>
                <span class="fw-bold text-dark d-block">I Made Agus</span>
                <small class="text-muted">0813-7766-5544</small>
              </div>
            </div>
          </td>
          <td><span class="badge-soft-primary small">Listrik & Panel</span></td>
          <td>Jakarta Selatan & Tangsel</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 4.8</span>
            <small class="text-muted">(192 Order)</small>
          </td>
          <td><span class="badge-soft-success small"><i class="bi bi-patch-check-fill me-1"></i>Terverifikasi</span></td>
          <td><span class="badge-soft-success small"><span class="status-dot online me-1"></span>Online</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Detail Mitra', 'Membuka profil I Made Agus', 'info')">
              Detail
            </button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" alt="Hendro">
              <div>
                <span class="fw-bold text-dark d-block">Hendro Wijaya</span>
                <small class="text-muted">0818-1234-9988</small>
              </div>
            </div>
          </td>
          <td><span class="badge-soft-primary small">Plumbing & Pipa</span></td>
          <td>Jakarta Timur & Bekasi</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 5.0</span>
            <small class="text-muted">(310 Order)</small>
          </td>
          <td><span class="badge-soft-success small"><i class="bi bi-patch-check-fill me-1"></i>Terverifikasi</span></td>
          <td><span class="badge-soft-secondary small"><span class="status-dot offline me-1"></span>Istirahat</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Detail Mitra', 'Membuka profil Hendro Wijaya', 'info')">
              Detail
            </button>
          </td>
        </tr>

        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=100&auto=format&fit=crop&q=80" class="rounded-circle object-fit-cover shadow-sm" style="width: 40px; height: 40px;" alt="Surya">
              <div>
                <span class="fw-bold text-dark d-block">Surya Saputra</span>
                <small class="text-muted">0852-3344-1122</small>
              </div>
            </div>
          </td>
          <td><span class="badge-soft-primary small">Elektronik & Mesin Cuci</span></td>
          <td>Jakarta Barat</td>
          <td>
            <span class="text-muted small">Mitra Baru</span>
          </td>
          <td><span class="badge-soft-warning small"><i class="bi bi-hourglass-split me-1"></i>Menunggu Review</span></td>
          <td><span class="badge-soft-secondary small">Offline</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-accent-green btn-sm" onclick="BetulinAja.showToast('Verifikasi Berhasil', 'Dokumen mitra Surya Saputra telah diverifikasi dan diaktifkan.', 'success')">
              Verifikasi
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
