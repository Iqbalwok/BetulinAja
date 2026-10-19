@extends('layouts.partner')

@section('title', 'Penghasilan & Dompet Mitra — BetulinAja')
@section('page_title', 'Ringkasan Pendapatan Mitra')

@section('partner_content')
<!-- Earnings Stat Cards -->
<div class="row g-3 g-md-4 mb-4">
  <div class="col-md-4">
    <div class="card card-custom p-4 h-100 bg-white border-primary">
      <span class="text-secondary small fw-medium d-block mb-1">Saldo Dapat Ditarik</span>
      <h3 class="fw-bold text-primary mb-2">Rp4.850.000</h3>
      <div class="d-flex align-items-center justify-content-between mt-3">
        <span class="badge-soft-success small"><i class="bi bi-shield-check me-1"></i>Siap Ditransfer</span>
        <button type="button" class="btn btn-primary-custom btn-sm px-3" data-bs-toggle="modal" data-bs-target="#withdrawModal">
          <i class="bi bi-cash-stack me-1"></i> Tarik Dana
        </button>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-custom p-4 h-100">
      <span class="text-secondary small fw-medium d-block mb-1">Total Pendapatan (Bulan Ini)</span>
      <h3 class="fw-bold text-dark mb-2">Rp6.420.000</h3>
      <span class="text-success small fw-medium"><i class="bi bi-arrow-up-right me-1"></i>+18.4% dari bulan lalu</span>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card card-custom p-4 h-100">
      <span class="text-secondary small fw-medium d-block mb-1">Total Order Selesai</span>
      <h3 class="fw-bold text-dark mb-2">42 Pekerjaan</h3>
      <span class="text-muted small">Rata-rata: Rp152.000 per order</span>
    </div>
  </div>
</div>

<!-- Transactions History Table -->
<div class="card card-custom p-4 mb-4">
  <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
    <div>
      <h5 class="fw-bold text-dark mb-0">Riwayat Rincian Komisi & Penarikan</h5>
      <span class="text-secondary small">Daftar transaksi masuk dari order selesai dan riwayat transfer bank.</span>
    </div>

    <div class="d-flex gap-2">
      <select class="form-select form-select-sm" id="earnings-filter-period">
        <option selected>Bulan Ini (Oktober 2026)</option>
        <option>Bulan Lalu (September 2026)</option>
        <option>Semua Riwayat</option>
      </select>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0 small">
      <thead class="table-light">
        <tr>
          <th>Tanggal & Waktu</th>
          <th>No. Pesanan / Transaksi</th>
          <th>Layanan Perbaikan</th>
          <th>Total Tagihan</th>
          <th>Komisi Bersih (85%)</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>19 Okt 2026, 14:45</td>
          <td><span class="fw-bold font-monospace text-primary">#ORD-849201</span></td>
          <td>Cuci AC Standar (0.5 - 1 PK)</td>
          <td>Rp150.000</td>
          <td class="fw-bold text-success">+Rp127.500</td>
          <td><span class="badge-soft-success small">Selesai</span></td>
        </tr>
        <tr>
          <td>18 Okt 2026, 11:20</td>
          <td><span class="fw-bold font-monospace text-primary">#ORD-842109</span></td>
          <td>Isi Freon R32 & Cek Kebocoran</td>
          <td>Rp175.000</td>
          <td class="fw-bold text-success">+Rp148.750</td>
          <td><span class="badge-soft-success small">Selesai</span></td>
        </tr>
        <tr>
          <td>17 Okt 2026, 09:00</td>
          <td><span class="fw-bold font-monospace text-secondary">#WD-90218</span></td>
          <td>Penarikan Dana ke Bank BCA (7281xxxxxx)</td>
          <td>-</td>
          <td class="fw-bold text-danger">-Rp1.500.000</td>
          <td><span class="badge-soft-primary small">Ditransfer</span></td>
        </tr>
        <tr>
          <td>16 Okt 2026, 16:30</td>
          <td><span class="fw-bold font-monospace text-primary">#ORD-839102</span></td>
          <td>Bongkar Pasang AC Daikin Inverter</td>
          <td>Rp250.000</td>
          <td class="fw-bold text-success">+Rp212.500</td>
          <td><span class="badge-soft-success small">Selesai</span></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- ==========================================
     MODAL TARIK SALDO DEMO
     ========================================== -->
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content card-custom border-0 p-4">
      <div class="modal-header border-bottom pb-3 pt-0 px-0">
        <h5 class="fw-bold text-dark mb-0" id="withdrawModalLabel">Tarik Saldo Pendapatan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body py-4 px-0">
        <div class="mb-3">
          <label class="form-label">Rekening Bank Tujuan</label>
          <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3">
            <i class="bi bi-bank2 text-primary fs-3"></i>
            <div>
              <span class="fw-bold text-dark d-block">Bank Central Asia (BCA)</span>
              <span class="text-secondary small">7281-9920-11 a.n. BUDI SANTOSO</span>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Nominal Penarikan (Rp)</label>
          <input type="number" class="form-control" value="1000000" min="50000" max="4850000">
          <span class="text-muted small">Saldo tersedia: Rp4.850.000 (Minimal penarikan Rp50.000)</span>
        </div>
      </div>
      <div class="modal-footer border-top pt-3 pb-0 px-0">
        <button type="button" class="btn btn-outline-custom" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary-custom" data-bs-dismiss="modal" onclick="BetulinAja.showToast('Penarikan Berhasil', 'Permintaan tarik saldo Rp1.000.000 sedang diproses ke rekening BCA Anda.', 'success')">
          Konfirmasi Penarikan
        </button>
      </div>
    </div>
  </div>
</div>
@endsection
