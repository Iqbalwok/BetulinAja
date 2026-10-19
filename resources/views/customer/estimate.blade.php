@extends('layouts.customer')

@section('title', 'Kalkulator Estimasi Biaya — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Simulasi Estimasi Biaya</li>
      </ol>
    </nav>
    <h3 class="fw-bold text-dark mb-1">Simulasi & Kalkulator Estimasi Biaya Jasa</h3>
    <p class="text-secondary small mb-0">Hitung perkiraan biaya perbaikan secara transparan sebelum memesan teknisi.</p>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4 justify-content-center">
    <!-- Simulator Controls -->
    <div class="col-lg-6">
      <div class="card card-custom p-4 p-md-5">
        <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Parameter Perhitungan</h5>

        <!-- Kategori -->
        <div class="mb-3">
          <label for="est-category" class="form-label">Kategori Perbaikan</label>
          <select id="est-category" class="form-select">
            <option value="100000" selected>Service AC & Pendingin (Mulai Rp100.000)</option>
            <option value="125000">Elektronik Rumah Tangga (Mulai Rp125.000)</option>
            <option value="120000">Instalasi & Kelistrikan (Mulai Rp120.000)</option>
            <option value="110000">Plumbing & Pipa Air (Mulai Rp110.000)</option>
            <option value="140000">Kulkas & Mesin Es (Mulai Rp140.000)</option>
            <option value="90000">Komputer & Laptop (Mulai Rp90.000)</option>
          </select>
        </div>

        <!-- Jarak Slider -->
        <div class="mb-4">
          <div class="d-flex justify-content-between mb-1">
            <label for="est-distance-range" class="form-label mb-0">Perkiraan Jarak Teknisi ke Rumah</label>
            <span class="fw-bold text-primary" id="est-distance-label">4 km</span>
          </div>
          <input type="range" class="form-range" id="est-distance-range" min="1" max="25" value="4" step="1">
          <div class="d-flex justify-content-between text-muted" style="font-size: 0.75rem;">
            <span>1 km</span>
            <span>Tarif: Rp5.000 / km</span>
            <span>25 km</span>
          </div>
        </div>

        <!-- Tingkat Kerusakan -->
        <div class="mb-4">
          <label class="form-label">Tingkat Kerusakan / Kompleksitas</label>
          <div class="row g-2">
            <div class="col-4">
              <input type="radio" class="btn-check" name="est-difficulty" id="diff-light" value="0" checked>
              <label class="btn btn-outline-custom w-100 text-center py-2" for="diff-light">
                <span class="d-block fw-bold small">Ringan</span>
                <span class="text-muted" style="font-size: 0.7rem;">+Rp0</span>
              </label>
            </div>
            <div class="col-4">
              <input type="radio" class="btn-check" name="est-difficulty" id="diff-medium" value="25000">
              <label class="btn btn-outline-custom w-100 text-center py-2" for="diff-medium">
                <span class="d-block fw-bold small">Sedang</span>
                <span class="text-muted" style="font-size: 0.7rem;">+Rp25.000</span>
              </label>
            </div>
            <div class="col-4">
              <input type="radio" class="btn-check" name="est-difficulty" id="diff-heavy" value="60000">
              <label class="btn btn-outline-custom w-100 text-center py-2" for="diff-heavy">
                <span class="d-block fw-bold small">Berat</span>
                <span class="text-muted" style="font-size: 0.7rem;">+Rp60.000</span>
              </label>
            </div>
          </div>
        </div>

        <button type="button" class="btn btn-outline-custom w-100" id="btn-reset-est">
          <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Nilai Simulasi
        </button>
      </div>
    </div>

    <!-- Output Card -->
    <div class="col-lg-5">
      <div class="card card-custom p-4 p-md-5 h-100 d-flex flex-column justify-content-between">
        <div>
          <span class="badge-soft-success small mb-2 d-inline-block">Perhitungan Real-time</span>
          <h5 class="fw-bold text-dark mb-4">Estimasi Tarif Transparan</h5>

          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between py-2 border-bottom small">
              <span class="text-secondary">Biaya Dasar Layanan</span>
              <span class="fw-bold text-dark" id="out-base">Rp100.000</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom small">
              <span class="text-secondary">Ongkos Transport (<span id="out-km">4 km</span> &times; Rp5.000)</span>
              <span class="fw-bold text-dark" id="out-travel">Rp20.000</span>
            </div>
            <div class="d-flex justify-content-between py-2 border-bottom small">
              <span class="text-secondary">Surcharge Kompleksitas</span>
              <span class="fw-bold text-dark" id="out-diff">Rp0</span>
            </div>
            <div class="d-flex justify-content-between pt-3 align-items-center">
              <div>
                <span class="fw-bold text-dark fs-6 d-block">Perkiraan Total</span>
                <span class="text-muted" style="font-size: 0.7rem;">Termasuk pajak</span>
              </div>
              <span class="fw-bold text-primary display-6 fs-3" id="out-total">Rp120.000</span>
            </div>
          </div>

          <div class="alert alert-light border rounded-3 small text-secondary mb-0">
            <i class="bi bi-shield-check text-success me-1"></i>
            <strong>Jaminan Transparansi:</strong> Biaya dasar melindungi Anda dari markup tidak wajar. Teknisi kami bekerja sesuai SOP baku BetulinAja.
          </div>
        </div>

        <div class="pt-4 border-top mt-4">
          <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom w-100 py-3">
            <i class="bi bi-calendar-check me-2"></i> Lanjutkan Pemesanan
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const catSelect = document.getElementById('est-category');
  const distRange = document.getElementById('est-distance-range');
  const distLabel = document.getElementById('est-distance-label');
  const diffRadios = document.querySelectorAll('input[name="est-difficulty"]');
  const resetBtn = document.getElementById('btn-reset-est');

  const outBase = document.getElementById('out-base');
  const outKm = document.getElementById('out-km');
  const outTravel = document.getElementById('out-travel');
  const outDiff = document.getElementById('out-diff');
  const outTotal = document.getElementById('out-total');

  function calculate() {
    const base = parseInt(catSelect.value);
    const km = parseInt(distRange.value);
    const travel = km * 5000;
    
    let diff = 0;
    diffRadios.forEach(r => {
      if (r.checked) diff = parseInt(r.value);
    });

    const total = base + travel + diff;

    distLabel.textContent = `${km} km`;
    outBase.textContent = BetulinAja.formatRupiah(base);
    outKm.textContent = `${km} km`;
    outTravel.textContent = BetulinAja.formatRupiah(travel);
    outDiff.textContent = BetulinAja.formatRupiah(diff);
    outTotal.textContent = BetulinAja.formatRupiah(total);
  }

  catSelect.addEventListener('change', calculate);
  distRange.addEventListener('input', calculate);
  diffRadios.forEach(r => r.addEventListener('change', calculate));

  if (resetBtn) {
    resetBtn.addEventListener('click', function() {
      catSelect.selectedIndex = 0;
      distRange.value = 4;
      document.getElementById('diff-light').checked = true;
      calculate();
      BetulinAja.showToast('Reset Selesai', 'Simulasi telah dikembalikan ke nilai default.', 'info');
    });
  }

  calculate();
});
</script>
@endpush
@endsection
