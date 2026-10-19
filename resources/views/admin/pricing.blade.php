@extends('layouts.admin')

@section('title', 'Pengaturan Tarif & Biaya — Admin BetulinAja')
@section('page_title', 'Konfigurasi Tarif & Rumus Estimasi Biaya')

@section('admin_content')
<div class="row g-4">
  <!-- Settings Form Column -->
  <div class="col-lg-7">
    <div class="card card-custom p-4 p-md-5 mb-4">
      <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
          <h5 class="fw-bold text-dark mb-0">Parameter Formula Tarif Platform</h5>
          <span class="text-secondary small">Tarif ini digunakan oleh kalkulator estimasi di sisi aplikasi pelanggan.</span>
        </div>
        <span class="badge-soft-primary small">Formula v2.4</span>
      </div>

      <form id="form-pricing-config" onsubmit="return false;">
        <!-- Base Fee Setting -->
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tag-fill text-primary me-2"></i>1. Tarif Dasar Acuan Layanan Standar</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Service AC Standar (Rp)</label>
            <input type="number" class="form-control" id="pricing-base-rate" value="100000" step="5000">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Kelistrikan Standar (Rp)</label>
            <input type="number" class="form-control" value="125000" step="5000">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Plumbing / Pipa Standar (Rp)</label>
            <input type="number" class="form-control" value="120000" step="5000">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Elektronik Standar (Rp)</label>
            <input type="number" class="form-control" value="130000" step="5000">
          </div>
        </div>

        <!-- Travel Fee Setting -->
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-bicycle text-success me-2"></i>2. Ongkos Perjalanan / Transportasi Mitra</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Tarif Transport per Kilometer (Rp/km)</label>
            <input type="number" class="form-control" id="pricing-km-rate" value="5000" step="500">
            <div class="form-text text-muted">Contoh: 5 km &times; Rp5.000 = Rp25.000</div>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Jarak Radius Maksimal Mitra (km)</label>
            <input type="number" class="form-control" value="15" min="1" max="50">
          </div>
        </div>

        <!-- Difficulty Surcharges -->
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-speedometer2 text-warning me-2"></i>3. Surcharge Tingkat Kesulitan</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-4">
            <label class="form-label">Ringan (Rp)</label>
            <input type="number" class="form-control" value="0" disabled>
          </div>
          <div class="col-sm-4">
            <label class="form-label">Sedang (Rp)</label>
            <input type="number" class="form-control" id="pricing-difficulty-rate" value="25000" step="5000">
          </div>
          <div class="col-sm-4">
            <label class="form-label">Berat (Rp)</label>
            <input type="number" class="form-control" value="60000" step="5000">
          </div>
        </div>

        <div class="pt-3 border-top d-flex align-items-center justify-content-between">
          <span class="text-muted small"><i class="bi bi-info-circle me-1"></i>Perubahan aktif seketika pada mode simulator demo.</span>
          <button type="button" class="btn btn-primary-custom px-4" id="btn-save-pricing">
            <i class="bi bi-save me-1"></i> Simpan Tarif Baru
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Live Simulator Preview Column -->
  <div class="col-lg-5">
    <div class="card card-custom p-4 p-md-5 sticky-lg-top" style="top: 100px;">
      <span class="badge-soft-success small mb-2 d-inline-block">Simulasi Real-Time</span>
      <h5 class="fw-bold text-dark mb-3">Live Preview Estimasi</h5>
      <p class="text-secondary small mb-4">Uji coba simulasi otomatis rumus tarif menggunakan parameter di sebelah kiri.</p>

      <div class="bg-light rounded-3 p-4 mb-4">
        <div class="d-flex justify-content-between py-2 border-bottom small">
          <span class="text-secondary">Simulasi Layanan</span>
          <span class="fw-bold text-dark">Cuci AC Standar</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom small">
          <span class="text-secondary">Sampel Jarak Mitra</span>
          <span class="fw-bold text-dark" id="sim-sample-distance">5 km</span>
        </div>
        <div class="d-flex justify-content-between py-2 border-bottom small">
          <span class="text-secondary">Tingkat Kesulitan</span>
          <span class="fw-bold text-dark">Sedang</span>
        </div>
        <div class="d-flex justify-content-between pt-3 align-items-center">
          <div>
            <span class="fw-bold text-dark d-block">Total Hasil Rumus</span>
            <small class="text-muted">Sebelum komisi 15%</small>
          </div>
          <span class="fw-bold text-primary fs-3" id="sim-calculated-total">Rp150.000</span>
        </div>
      </div>

      <div class="alert alert-light border small text-secondary mb-0">
        <i class="bi bi-shield-check text-success me-1"></i>
        <strong>Kebijakan Proteksi Tarif:</strong> Fluktuasi tarif dasar dibatasi maksimal 20% dalam 24 jam untuk melindungi kepastian harga bagi pelanggan.
      </div>
    </div>
  </div>
</div>
@endsection
