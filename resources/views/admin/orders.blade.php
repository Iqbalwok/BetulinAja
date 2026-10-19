@extends('layouts.admin')

@section('title', 'Manajemen Pesanan — Admin BetulinAja')
@section('page_title', 'Daftar Semua Pesanan Transaksi')

@section('admin_content')
<div class="admin-table-wrapper p-4 mb-4">
  <!-- Filters Bar -->
  <div class="row g-3 align-items-center mb-4">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" id="admin-table-search" class="form-control border-start-0" placeholder="Cari nomor pesanan, pelanggan, atau teknisi...">
      </div>
    </div>
    <div class="col-md-4">
      <select class="form-select" id="admin-status-filter">
        <option value="all" selected>Semua Status Pesanan</option>
        <option value="perjalanan">Dalam Perjalanan</option>
        <option value="selesai">Selesai</option>
        <option value="cari">Mencari Teknisi</option>
        <option value="batal">Dibatalkan</option>
      </select>
    </div>
    <div class="col-md-3 text-md-end">
      <button type="button" class="btn btn-outline-custom btn-sm w-100 w-md-auto" onclick="BetulinAja.showToast('Ekspor Data', 'Mengunduh laporan pesanan berformat CSV (Demo)...', 'info')">
        <i class="bi bi-download me-1"></i> Ekspor CSV
      </button>
    </div>
  </div>

  <!-- Orders Table -->
  <div class="table-responsive">
    <table class="table admin-table filterable-table align-middle">
      <thead>
        <tr>
          <th>No. Pesanan</th>
          <th>Tanggal & Jam</th>
          <th>Pelanggan</th>
          <th>Layanan Perbaikan</th>
          <th>Mitra Teknisi</th>
          <th>Total Biaya</th>
          <th>Status</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr data-status="perjalanan">
          <td class="font-monospace fw-bold text-primary">#ORD-849201</td>
          <td>19 Okt 2026, 14:00</td>
          <td>
            <span class="fw-semibold d-block text-dark">Ahmad Fauzi</span>
            <small class="text-muted">Kebayoran Baru, Jaksel</small>
          </td>
          <td>Cuci AC Standar (0.5 - 1 PK)</td>
          <td>
            <span class="d-block text-dark fw-medium">Budi Santoso</span>
            <small class="text-warning"><i class="bi bi-star-fill"></i> 4.9</small>
          </td>
          <td class="fw-bold">Rp150.000</td>
          <td><span class="badge-soft-primary small">Dalam Perjalanan</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
              Detail
            </button>
          </td>
        </tr>

        <tr data-status="cari">
          <td class="font-monospace fw-bold text-primary">#ORD-892134</td>
          <td>19 Okt 2026, 13:45</td>
          <td>
            <span class="fw-semibold d-block text-dark">Dewi Kartika</span>
            <small class="text-muted">Senopati, Jaksel</small>
          </td>
          <td>Tambah Freon R32 AC</td>
          <td><span class="text-muted fst-italic">Mencari Mitra...</span></td>
          <td class="fw-bold">Rp175.000</td>
          <td><span class="badge-soft-warning small">Mencari Teknisi</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
              Detail
            </button>
          </td>
        </tr>

        <tr data-status="selesai">
          <td class="font-monospace fw-bold text-primary">#ORD-781923</td>
          <td>18 Okt 2026, 10:30</td>
          <td>
            <span class="fw-semibold d-block text-dark">Bambang Setiawan</span>
            <small class="text-muted">Cilandak, Jaksel</small>
          </td>
          <td>Perbaikan Korsleting MCB</td>
          <td>
            <span class="d-block text-dark fw-medium">I Made Agus</span>
            <small class="text-warning"><i class="bi bi-star-fill"></i> 4.8</small>
          </td>
          <td class="fw-bold">Rp145.000</td>
          <td><span class="badge-soft-success small">Selesai</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
              Detail
            </button>
          </td>
        </tr>

        <tr data-status="selesai">
          <td class="font-monospace fw-bold text-primary">#ORD-629104</td>
          <td>17 Okt 2026, 16:15</td>
          <td>
            <span class="fw-semibold d-block text-dark">Rina Melati</span>
            <small class="text-muted">Tebet, Jaksel</small>
          </td>
          <td>Pelancaran Pipa Mampet</td>
          <td>
            <span class="d-block text-dark fw-medium">Hendro Wijaya</span>
            <small class="text-warning"><i class="bi bi-star-fill"></i> 5.0</small>
          </td>
          <td class="fw-bold">Rp120.000</td>
          <td><span class="badge-soft-success small">Selesai</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
              Detail
            </button>
          </td>
        </tr>

        <tr data-status="batal">
          <td class="font-monospace fw-bold text-secondary">#ORD-510294</td>
          <td>15 Okt 2026, 09:10</td>
          <td>
            <span class="fw-semibold d-block text-dark">Hendra Kusuma</span>
            <small class="text-muted">Kelapa Gading, Jakut</small>
          </td>
          <td>Service TV LED Layar Gelap</td>
          <td><span class="text-muted">-</span></td>
          <td class="fw-bold">Rp150.000</td>
          <td><span class="badge-soft-danger small">Dibatalkan</span></td>
          <td class="text-end">
            <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
              Detail
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Pagination Demo -->
  <div class="d-flex flex-column flex-sm-row align-items-center justify-content-between gap-2 mt-4 pt-3 border-top small">
    <span class="text-secondary">Menampilkan 5 dari 1.482 total pesanan</span>
    <nav>
      <ul class="pagination pagination-sm mb-0">
        <li class="page-item disabled"><a class="page-link" href="#">Sebelumnya</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li>
        <li class="page-item"><a class="page-link" href="#">Berikutnya</a></li>
      </ul>
    </nav>
  </div>
</div>

<!-- ==========================================
     MODAL DETAIL PESANAN ADMIN
     ========================================== -->
<div class="modal fade" id="adminOrderDetailModal" tabindex="-1" aria-labelledby="adminOrderDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content card-custom border-0 p-4">
      <div class="modal-header border-bottom pb-3 pt-0 px-0">
        <div class="d-flex align-items-center gap-2">
          <h5 class="fw-bold text-dark mb-0" id="adminOrderDetailModalLabel">Manajemen Pesanan #ORD-849201</h5>
          <span class="badge-soft-primary small">Dalam Perjalanan</span>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body py-4 px-0">
        <div class="row g-4 mb-4">
          <div class="col-sm-6">
            <h6 class="fw-bold text-dark small text-uppercase mb-2 text-muted">Data Pelanggan</h6>
            <div class="p-3 rounded-3 bg-light">
              <span class="fw-bold text-dark d-block">Ahmad Fauzi</span>
              <span class="text-secondary small d-block">0812-9876-5432 &bull; ahmad@example.com</span>
              <span class="text-dark small d-block mt-2">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</span>
            </div>
          </div>

          <div class="col-sm-6">
            <h6 class="fw-bold text-dark small text-uppercase mb-2 text-muted">Mitra Ditugaskan</h6>
            <div class="p-3 rounded-3 bg-light">
              <span class="fw-bold text-dark d-block">Budi Santoso</span>
              <span class="text-secondary small d-block">0812-3456-7890 &bull; ID: #MITRA-8291</span>
              <span class="badge-soft-success small mt-2 d-inline-block">Rating: 4.9 (142 Ulasan)</span>
            </div>
          </div>
        </div>

        <h6 class="fw-bold text-dark small text-uppercase mb-2 text-muted">Rincian Finansial Transaksi</h6>
        <div class="table-responsive bg-light rounded-3 p-3 mb-3">
          <table class="table table-borderless small mb-0">
            <tbody>
              <tr>
                <td>Tarif Dasar Layanan</td>
                <td class="text-end fw-semibold">Rp100.000</td>
              </tr>
              <tr>
                <td>Biaya Transportasi (5 km)</td>
                <td class="text-end fw-semibold">Rp25.000</td>
              </tr>
              <tr>
                <td>Surcharge Kesulitan (Sedang)</td>
                <td class="text-end fw-semibold">Rp25.000</td>
              </tr>
              <tr class="border-top">
                <td class="fw-bold fs-6">Total Nilai Pesanan</td>
                <td class="text-end fw-bold text-primary fs-6">Rp150.000</td>
              </tr>
              <tr>
                <td class="text-success">Bagi Hasil Mitra (85%)</td>
                <td class="text-end text-success fw-bold">Rp127.500</td>
              </tr>
              <tr>
                <td class="text-secondary">Fee Komisi Platform BetulinAja (15%)</td>
                <td class="text-end text-dark fw-bold">Rp22.500</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="modal-footer border-top pt-3 pb-0 px-0 d-flex justify-content-between">
        <button type="button" class="btn btn-outline-danger btn-sm" onclick="BetulinAja.showToast('Pembatalan', 'Pesanan dibatalkan oleh Admin.', 'warning');">
          Batalkan Pesanan
        </button>
        <div>
          <button type="button" class="btn btn-outline-custom btn-sm me-2" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-primary-custom btn-sm" data-bs-dismiss="modal" onclick="BetulinAja.showToast('Update Status', 'Status pesanan berhasil disinkronkan.', 'success');">
            Simpan Perubahan
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
