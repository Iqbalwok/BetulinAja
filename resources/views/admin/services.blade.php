@extends('layouts.admin')

@section('title', 'Katalog Layanan & Kategori — Admin BetulinAja')
@section('page_title', 'Kelola Kategori & Layanan Service')

@section('admin_content')
<div class="admin-table-wrapper p-4 mb-4">
  <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Daftar Layanan Aktif di Platform</h5>
      <span class="text-secondary small">Atur nama perbaikan, deskripsi, tarif dasar acuan, dan status visibilitas.</span>
    </div>
    <button type="button" class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#addServiceModal">
      <i class="bi bi-plus-circle me-1"></i> Tambah Layanan Baru
    </button>
  </div>

  <div class="table-responsive">
    <table class="table admin-table align-middle">
      <thead>
        <tr>
          <th>Kategori</th>
          <th>Nama Layanan</th>
          <th>Tarif Dasar Acuan</th>
          <th>Pekerjaan Selesai</th>
          <th>Status Visibilitas</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><span class="badge-soft-primary small">Service AC</span></td>
          <td class="fw-semibold text-dark">Cuci AC Standar (0.5 - 1 PK)</td>
          <td class="fw-bold">Rp100.000</td>
          <td>542 Kali</td>
          <td><span class="badge-soft-success small">Aktif Tayang</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Edit Layanan', 'Membuka form ubah layanan...', 'info')">
              Edit
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="badge-soft-primary small">Service AC</span></td>
          <td class="fw-semibold text-dark">Isi / Tambah Freon R32/R410</td>
          <td class="fw-bold">Rp175.000</td>
          <td>288 Kali</td>
          <td><span class="badge-soft-success small">Aktif Tayang</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Edit Layanan', 'Membuka form ubah layanan...', 'info')">
              Edit
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="badge-soft-warning small text-dark">Listrik</span></td>
          <td class="fw-semibold text-dark">Perbaikan Korsleting MCB</td>
          <td class="fw-bold">Rp125.000</td>
          <td>194 Kali</td>
          <td><span class="badge-soft-success small">Aktif Tayang</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Edit Layanan', 'Membuka form ubah layanan...', 'info')">
              Edit
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="badge-soft-info small">Plumbing</span></td>
          <td class="fw-semibold text-dark">Pelancaran Pipa Mampet</td>
          <td class="fw-bold">Rp120.000</td>
          <td>165 Kali</td>
          <td><span class="badge-soft-success small">Aktif Tayang</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Edit Layanan', 'Membuka form ubah layanan...', 'info')">
              Edit
            </button>
          </td>
        </tr>
        <tr>
          <td><span class="badge-soft-danger small">Elektronik</span></td>
          <td class="fw-semibold text-dark">Perbaikan Mesin Cuci 1/2 Tabung</td>
          <td class="fw-bold">Rp130.000</td>
          <td>112 Kali</td>
          <td><span class="badge-soft-success small">Aktif Tayang</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Edit Layanan', 'Membuka form ubah layanan...', 'info')">
              Edit
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ==========================================
     MODAL TAMBAH LAYANAN DEMO
     ========================================== -->
<div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-custom border-0 p-4">
      <div class="modal-header border-bottom pb-3 pt-0 px-0">
        <h5 class="fw-bold text-dark mb-0" id="addServiceModalLabel">Tambah Layanan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 px-0">
        <div class="mb-3">
          <label class="form-label">Pilih Kategori</label>
          <select class="form-select">
            <option>Service AC</option>
            <option>Elektronik</option>
            <option>Listrik</option>
            <option>Plumbing</option>
            <option>Kulkas</option>
            <option>Komputer</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Nama Layanan</label>
          <input type="text" class="form-control" placeholder="Contoh: Service Dispenser Air Galon">
        </div>
        <div class="mb-3">
          <label class="form-label">Tarif Dasar Acuan (Rp)</label>
          <input type="number" class="form-control" placeholder="Contoh: 110000">
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi Singkat</label>
          <textarea class="form-control" rows="3" placeholder="Pembersihan tangki, penggantian kran dan pemanas..."></textarea>
        </div>
      </div>
      <div class="modal-footer border-top pt-3 pb-0 px-0">
        <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary-custom" data-bs-dismiss="modal" onclick="BetulinAja.showToast('Layanan Ditambahkan', 'Layanan baru berhasil disimpan ke katalog demo.', 'success');">
          Simpan Layanan
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
