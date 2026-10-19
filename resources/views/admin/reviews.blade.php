@extends('layouts.admin')

@section('title', 'Ulasan & Rating Pelanggan — Admin BetulinAja')
@section('page_title', 'Moderasi Ulasan & Penilaian Pelanggan')

@section('admin_content')
<div class="admin-table-wrapper p-4 mb-4">
  <div class="row g-3 align-items-center mb-4">
    <div class="col-md-6">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" class="form-control border-start-0" placeholder="Cari isi ulasan atau nama mitra...">
      </div>
    </div>
    <div class="col-md-6 text-md-end">
      <span class="badge-soft-success py-1 px-3 small">
        <i class="bi bi-star-fill text-warning me-1"></i> Rata-rata Rating Platform: 4.9 / 5.0
      </span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table admin-table align-middle">
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Pelanggan</th>
          <th>Mitra Teknisi</th>
          <th>Rating</th>
          <th>Komentar / Ulasan</th>
          <th>Status Moderasi</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="small text-muted">19 Okt 2026</td>
          <td><span class="fw-semibold text-dark">Ahmad Fauzi</span></td>
          <td>Budi Santoso</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 5.0</span>
          </td>
          <td class="small text-secondary">"Teknisi datang tepat waktu, pengerjaan rapi dan AC langsung dingin kembali. Recommended!"</td>
          <td><span class="badge-soft-success small">Ditampilkan</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Moderasi', 'Status ulasan disesuaikan.', 'info')">
              Sembunyikan
            </button>
          </td>
        </tr>
        <tr>
          <td class="small text-muted">18 Okt 2026</td>
          <td><span class="fw-semibold text-dark">Dewi Kartika</span></td>
          <td>I Made Agus</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 5.0</span>
          </td>
          <td class="small text-secondary">"Korsleting listrik rumah cepat teratasi, penjelasannya detail dan sangat sopan."</td>
          <td><span class="badge-soft-success small">Ditampilkan</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Moderasi', 'Status ulasan disesuaikan.', 'info')">
              Sembunyikan
            </button>
          </td>
        </tr>
        <tr>
          <td class="small text-muted">17 Okt 2026</td>
          <td><span class="fw-semibold text-dark">Rina Melati</span></td>
          <td>Hendro Wijaya</td>
          <td>
            <span class="text-warning fw-bold small"><i class="bi bi-star-fill"></i> 4.5</span>
          </td>
          <td class="small text-secondary">"Pipa wastafel lancar kembali tanpa harus membongkar ubin dapur. Mantap!"</td>
          <td><span class="badge-soft-success small">Ditampilkan</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Moderasi', 'Status ulasan disesuaikan.', 'info')">
              Sembunyikan
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection
