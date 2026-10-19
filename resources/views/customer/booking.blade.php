@extends('layouts.customer')

@section('title', 'Pemesanan Jasa Service — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/customer/services') }}" class="text-decoration-none text-secondary">Layanan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Form Pemesanan</li>
      </ol>
    </nav>
    <h3 class="fw-bold text-dark mb-0">Pemesanan Jasa Service & Perbaikan</h3>
  </div>
</div>

<div class="container py-4 py-md-5" id="booking-wizard-container">

  <!-- Desktop Progress Stepper -->
  <div class="booking-progress-container mb-4">
    <div class="booking-step-pills">
      <div class="booking-step-item active" id="step-pill-1">
        <div class="step-circle">1</div>
        <span class="step-label">Detail Kerusakan</span>
      </div>
      <div class="booking-step-item" id="step-pill-2">
        <div class="step-circle">2</div>
        <span class="step-label">Lokasi & Jadwal</span>
      </div>
      <div class="booking-step-item" id="step-pill-3">
        <div class="step-circle">3</div>
        <span class="step-label">Estimasi Biaya</span>
      </div>
      <div class="booking-step-item" id="step-pill-4">
        <div class="step-circle">4</div>
        <span class="step-label">Pilih Teknisi</span>
      </div>
      <div class="booking-step-item" id="step-pill-5">
        <div class="step-circle">5</div>
        <span class="step-label">Konfirmasi</span>
      </div>
    </div>

    <!-- Mobile Compact Step Indicator -->
    <div class="mobile-step-indicator shadow-sm">
      <div class="d-flex align-items-center justify-content-between mb-1">
        <span class="fw-bold text-primary small" id="mobile-current-step">Langkah 1 dari 5</span>
        <span class="badge-soft-primary small">Pemesanan</span>
      </div>
      <h6 class="fw-bold text-dark mb-0" id="mobile-step-title">Detail Kerusakan</h6>
    </div>
  </div>

  <div class="row justify-content-center">
    <div class="col-lg-9 col-xl-8">

      <!-- ==========================================
           TAHAP 1: DETAIL KERUSAKAN
           ========================================== -->
      <div class="wizard-step-pane" id="step-pane-1">
        <div class="card card-custom p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="category-icon-box m-0 bg-primary text-white" style="width: 44px; height: 44px;">
              <i class="bi bi-tools fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Tahap 1: Detail Kerusakan & Layanan</h5>
              <span class="text-secondary small">Jelaskan kendala Anda agar teknisi dapat menyiapkan peralatan dengan akurat.</span>
            </div>
          </div>

          <form id="form-step-1" onsubmit="return false;">
            <!-- Dropdown Kategori -->
            <div class="mb-3">
              <label for="category-select" class="form-label">Kategori Layanan <span class="text-danger">*</span></label>
              <select class="form-select" id="category-select" required>
                <option value="Service AC" selected>Service AC & Pendingin</option>
                <option value="Elektronik">Elektronik Rumah Tangga</option>
                <option value="Listrik">Kelistrikan & Saklar</option>
                <option value="Plumbing">Plumbing & Pipa Air</option>
                <option value="Kulkas">Kulkas & Freezer</option>
                <option value="Komputer">Komputer & Laptop</option>
              </select>
            </div>

            <!-- Dropdown Jenis Layanan -->
            <div class="mb-4">
              <label for="service-select" class="form-label">Pilihan Tindakan / Perbaikan <span class="text-danger">*</span></label>
              <select class="form-select" id="service-select" required>
                <option value="Cuci AC Standar (0.5 - 1 PK)" data-base="100000" selected>Cuci AC Standar (0.5 - 1 PK) (Rp100.000)</option>
                <option value="Tambah / Isi Freon R32/R410" data-base="175000">Tambah / Isi Freon R32/R410 (Rp175.000)</option>
                <option value="Bongkar Pasang AC" data-base="250000">Bongkar Pasang AC (Rp250.000)</option>
                <option value="Perbaikan AC Bocor / Berisik" data-base="150000">Perbaikan AC Bocor / Berisik (Rp150.000)</option>
              </select>
            </div>

            <!-- Deskripsi Kendala -->
            <div class="mb-4">
              <label for="problem-description" class="form-label">Jelaskan Kendala atau Kerusakan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="problem-description" rows="3" placeholder="Contoh: AC kamar tidak dingin dan meneteskan air di sisi kanan indoor sejak 2 hari lalu..." required></textarea>
              <div class="form-text text-muted">Deskripsi yang detail membantu teknisi mengestimasi waktu pengerjaan.</div>
            </div>

            <!-- Upload Foto Kerusakan -->
            <div class="mb-3">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <label class="form-label mb-0">Upload Foto Kerusakan (Opsional tapi Disarankan)</label>
                <span class="badge-soft-secondary small" id="photo-count-badge">0/5 Foto Diunggah</span>
              </div>

              <input type="file" id="photo-upload-input" multiple accept="image/*" class="d-none">

              <div class="photo-dropzone" id="photo-dropzone">
                <i class="bi bi-cloud-arrow-up text-primary fs-1 mb-2 d-block"></i>
                <h6 class="fw-semibold text-dark mb-1">Klik atau seret foto kerusakan ke area ini</h6>
                <p class="text-secondary small mb-0">Mendukung format JPG, PNG, atau WebP (Maksimal 5 foto)</p>
              </div>

              <!-- Preview Grid -->
              <div class="photo-preview-grid" id="photo-preview-grid"></div>
            </div>
          </form>
        </div>
      </div>

      <!-- ==========================================
           TAHAP 2: LOKASI DAN JADWAL
           ========================================== -->
      <div class="wizard-step-pane d-none" id="step-pane-2">
        <div class="card card-custom p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="category-icon-box m-0 bg-success text-white" style="width: 44px; height: 44px;">
              <i class="bi bi-geo-alt fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Tahap 2: Lokasi Perbaikan & Jadwal Kedatangan</h5>
              <span class="text-secondary small">Tentukan lokasi akurat dan waktu kunjungan teknisi.</span>
            </div>
          </div>

          <form id="form-step-2" onsubmit="return false;">
            <!-- Alamat Lengkap -->
            <div class="mb-3">
              <label for="address-input" class="form-label">Alamat Lengkap Perbaikan <span class="text-danger">*</span></label>
              <textarea class="form-control" id="address-input" rows="2" placeholder="Contoh: Jl. Sudirman No. 45, RT 02/RW 04, Kebayoran Baru, Jakarta Selatan" required>Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</textarea>
            </div>

            <!-- Patokan Alamat -->
            <div class="mb-4">
              <label for="address-notes" class="form-label">Patokan atau Petunjuk Khusus</label>
              <input type="text" class="form-control" id="address-notes" value="Pagar warna hitam, samping minimarket Alfamart" placeholder="Contoh: Rumah pagar hitam, seberang masjid...">
            </div>

            <!-- Peta Leaflet Interaktif -->
            <div class="mb-4">
              <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-2">
                <label class="form-label mb-0">Tandai Titik Lokasi di Peta</label>
                <button type="button" class="btn btn-sm btn-outline-custom" id="btn-current-location">
                  <i class="bi bi-crosshair me-1"></i> Gunakan Lokasi Saat Ini
                </button>
              </div>

              <div id="booking-leaflet-map" class="map-container mb-2"></div>
              <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Geser pin biru atau klik di peta untuk mengatur lokasi presisi kedatangan teknisi.</small>
            </div>

            <!-- Pilihan Jadwal -->
            <div class="mb-3">
              <label class="form-label d-block">Pilihan Waktu Kunjungan</label>
              <div class="row g-2">
                <div class="col-sm-6">
                  <input type="radio" class="btn-check" name="schedule-choice" id="schedule-now" checked>
                  <label class="btn btn-outline-custom w-100 p-3 text-start d-flex align-items-center gap-3" for="schedule-now">
                    <i class="bi bi-lightning-charge-fill text-warning fs-4"></i>
                    <div>
                      <span class="d-block fw-bold text-dark">Datang Sekarang (Instan)</span>
                      <small class="text-secondary">Teknisi meluncur dalam 20 - 45 menit</small>
                    </div>
                  </label>
                </div>
                <div class="col-sm-6">
                  <input type="radio" class="btn-check" name="schedule-choice" id="schedule-later">
                  <label class="btn btn-outline-custom w-100 p-3 text-start d-flex align-items-center gap-3" for="schedule-later">
                    <i class="bi bi-calendar-event text-primary fs-4"></i>
                    <div>
                      <span class="d-block fw-bold text-dark">Jadwalkan Kunjungan</span>
                      <small class="text-secondary">Pilih tanggal dan jam tertentu</small>
                    </div>
                  </label>
                </div>
              </div>
            </div>

            <!-- Jadwal Input (Muncul bila pilih kemudian) -->
            <div class="row g-3 d-none" id="scheduled-inputs">
              <div class="col-sm-6">
                <label class="form-label">Pilih Tanggal</label>
                <input type="date" class="form-control" id="schedule-date-input" value="{{ date('Y-m-d') }}">
              </div>
              <div class="col-sm-6">
                <label class="form-label">Pilih Waktu</label>
                <select class="form-select" id="schedule-time-input">
                  <option>08:00 - 10:00 WIB</option>
                  <option selected>10:00 - 12:00 WIB</option>
                  <option>13:00 - 15:00 WIB</option>
                  <option>15:00 - 17:00 WIB</option>
                  <option>19:00 - 21:00 WIB</option>
                </select>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ==========================================
           TAHAP 3: ESTIMASI BIAYA
           ========================================== -->
      <div class="wizard-step-pane d-none" id="step-pane-3">
        <div class="card card-custom p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="category-icon-box m-0 bg-warning text-dark" style="width: 44px; height: 44px;">
              <i class="bi bi-cash-coin fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Tahap 3: Perhitungan Estimasi Biaya</h5>
              <span class="text-secondary small">Rincian transparan berdasarkan jenis jasa, jarak radius teknisi, dan tingkat kendala.</span>
            </div>
          </div>

          <!-- Tingkat Kesulitan Surcharge Selector -->
          <div class="mb-4">
            <label class="form-label">Prakiraan Tingkat Kesulitan</label>
            <select class="form-select" id="difficulty-select">
              <option value="ringan">Ringan — Masalah Standar / Pembersihan Ringan (+Rp0)</option>
              <option value="sedang" selected>Sedang — Butuh Pengecekan & Penggantian Ringan (+Rp25.000)</option>
              <option value="berat">Berat — Bongkar Total / Kerusakan Jalur Utama (+Rp60.000)</option>
            </select>
          </div>

          <!-- Rincian Biaya Box -->
          <div class="bg-light border rounded-3 p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3">Rincian Komponen Biaya</h6>
            
            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Biaya Dasar Layanan</span>
              <span class="fw-semibold text-dark" id="calc-base-price">Rp100.000</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Jarak Tempuh Teknisi (<span id="calc-distance">5 km</span>)</span>
              <span class="fw-semibold text-dark" id="calc-travel-fee">Rp25.000</span>
            </div>

            <div class="d-flex justify-content-between py-2 border-bottom">
              <span class="text-secondary">Biaya Tingkat Kesulitan</span>
              <span class="fw-semibold text-dark" id="calc-difficulty-fee">Rp25.000</span>
            </div>

            <div class="d-flex justify-content-between pt-3 align-items-center">
              <div>
                <span class="fw-bold text-dark fs-5 d-block">Estimasi Total</span>
                <span class="text-muted small">Sudah termasuk PPN 11%</span>
              </div>
              <span class="fw-bold text-primary display-6 fs-3" id="calc-total-estimate">Rp150.000</span>
            </div>
          </div>

          <!-- Catatan Transparansi -->
          <div class="alert alert-info border-0 rounded-3 d-flex align-items-start gap-2 mb-0" style="background-color: var(--primary-light);">
            <i class="bi bi-info-circle-fill text-primary fs-5 mt-1 flex-shrink-0"></i>
            <div class="small text-secondary">
              <strong>Catatan Transparansi:</strong> Estimasi ini merupakan angka acuan awal. Biaya dapat berubah apabila saat pemeriksaan fisik teknisi menemukan kebutuhan pergantian spare part asli atau kondisi kerusakan luar dugaan. Teknisi wajib meminta persetujuan Anda sebelum tindakan tambahan dilakukan.
            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAHAP 4: PILIH TEKNISI
           ========================================== -->
      <div class="wizard-step-pane d-none" id="step-pane-4">
        <div class="card card-custom p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="category-icon-box m-0 bg-info text-white" style="width: 44px; height: 44px;">
              <i class="bi bi-person-check fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Tahap 4: Pilih Teknisi</h5>
              <span class="text-secondary small">Gunakan sistem otomatis rekomendasi terbaik atau pilih teknisi pilihan Anda sendiri.</span>
            </div>
          </div>

          <div class="row g-3 mb-4">
            <!-- Option 1: Auto Assign (Recommended) -->
            <div class="col-12">
              <div class="technician-selection-card tech-select-card selected" data-id="auto" data-name="Sistem Otomatis (Rekomendasi Terbaik)">
                <div class="d-flex align-items-center gap-3">
                  <div class="category-icon-box m-0 bg-primary text-white" style="width: 48px; height: 48px;">
                    <i class="bi bi-cpu fs-4"></i>
                  </div>
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                      <h6 class="fw-bold text-dark mb-0">Penugasan Otomatis Sistem</h6>
                      <span class="badge-soft-primary small py-1 px-2">Disarankan</span>
                    </div>
                    <p class="text-secondary small mb-0">
                      Sistem demo memilih teknisi terdekat dengan rating tertinggi dan ketersediaan tercepat secara otomatis.
                    </p>
                  </div>
                  <div class="text-end d-none d-sm-block">
                    <span class="badge-soft-success py-1 px-3 small">Paling Cepat</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Option 2: Teknisi 1 -->
            <div class="col-12">
              <div class="technician-selection-card tech-select-card" data-id="1" data-name="Budi Santoso" data-rating="4.9" data-specialty="Master AC & Pendingin" data-eta="25 Menit">
                <div class="d-flex align-items-center gap-3">
                  <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=150&auto=format&fit=crop&q=80" alt="Budi Santoso" class="rounded-circle object-fit-cover shadow-sm" style="width: 50px; height: 50px;">
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                      <h6 class="fw-bold text-dark mb-0">Budi Santoso</h6>
                      <i class="bi bi-patch-check-fill text-primary small"></i>
                      <span class="text-warning small fw-bold"><i class="bi bi-star-fill"></i> 4.9 (142)</span>
                    </div>
                    <span class="text-secondary small d-block">Spesialisasi: AC & Mesin Pendingin</span>
                    <span class="text-muted small"><i class="bi bi-geo-alt me-1 text-danger"></i>2.4 km &bull; Est. Tiba: 25 Menit</span>
                  </div>
                  <div>
                    <span class="badge-soft-success small">Tersedia</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Option 3: Teknisi 2 -->
            <div class="col-12">
              <div class="technician-selection-card tech-select-card" data-id="2" data-name="I Made Agus" data-rating="4.8" data-specialty="Elektronik & Listrik" data-eta="35 Menit">
                <div class="d-flex align-items-center gap-3">
                  <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80" alt="I Made Agus" class="rounded-circle object-fit-cover shadow-sm" style="width: 50px; height: 50px;">
                  <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                      <h6 class="fw-bold text-dark mb-0">I Made Agus</h6>
                      <i class="bi bi-patch-check-fill text-primary small"></i>
                      <span class="text-warning small fw-bold"><i class="bi bi-star-fill"></i> 4.8 (98)</span>
                    </div>
                    <span class="text-secondary small d-block">Spesialisasi: Elektronik Rumah Tangga & Kelistrikan</span>
                    <span class="text-muted small"><i class="bi bi-geo-alt me-1 text-danger"></i>3.1 km &bull; Est. Tiba: 35 Menit</span>
                  </div>
                  <div>
                    <span class="badge-soft-success small">Tersedia</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ==========================================
           TAHAP 5: KONFIRMASI PESANAN
           ========================================== -->
      <div class="wizard-step-pane d-none" id="step-pane-5">
        <div class="card card-custom p-4 p-md-5">
          <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
            <div class="category-icon-box m-0 bg-success text-white" style="width: 44px; height: 44px;">
              <i class="bi bi-clipboard-check fs-5"></i>
            </div>
            <div>
              <h5 class="fw-bold text-dark mb-0">Tahap 5: Konfirmasi Akhir Pesanan</h5>
              <span class="text-secondary small">Periksa kembali ringkasan pesanan sebelum menugaskan teknisi.</span>
            </div>
          </div>

          <div class="bg-light border rounded-3 p-4 mb-4">
            <div class="row g-3">
              <div class="col-sm-6">
                <span class="text-muted small d-block">Layanan Dipilih</span>
                <span class="fw-bold text-dark" id="summary-service">Service AC - Cuci AC Standar</span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted small d-block">Teknisi / Penugasan</span>
                <span class="fw-bold text-dark" id="summary-technician">Sistem Otomatis</span>
              </div>
              <div class="col-12 border-top pt-2">
                <span class="text-muted small d-block">Alamat Lokasi</span>
                <span class="text-dark small" id="summary-address">Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan</span>
              </div>
              <div class="col-12 border-top pt-2">
                <span class="text-muted small d-block">Kendala / Keluhan</span>
                <span class="text-dark small fst-italic" id="summary-description">-</span>
              </div>
              <div class="col-12 border-top pt-2">
                <span class="text-muted small d-block mb-1">Foto Bukti Kerusakan</span>
                <div class="d-flex gap-2 flex-wrap" id="summary-photos-list"></div>
              </div>
              <div class="col-12 border-top pt-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark fs-5">Estimasi Total Biaya</span>
                <span class="fw-bold text-primary fs-4" id="summary-total">Rp150.000</span>
              </div>
            </div>
          </div>

          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terms-agree" checked>
            <label class="form-check-label text-secondary small" for="terms-agree">
              Saya menyetujui bahwa estimasi biaya dapat disesuaikan di lokasi atas persetujuan bersama jika terdapat penggantian suku cadang.
            </label>
          </div>
        </div>
      </div>

      <!-- Action Navigation Buttons -->
      <div class="d-flex align-items-center justify-content-between mt-4">
        <button type="button" class="btn btn-outline-custom px-4 d-none" id="btn-wizard-prev">
          <i class="bi bi-arrow-left me-2"></i> Kembali
        </button>
        <button type="button" class="btn btn-primary-custom px-4 ms-auto" id="btn-wizard-next">
          Lanjut <i class="bi bi-arrow-right ms-2"></i>
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const scheduleNow = document.getElementById('schedule-now');
  const scheduleLater = document.getElementById('schedule-later');
  const scheduledInputs = document.getElementById('scheduled-inputs');

  if (scheduleLater && scheduledInputs) {
    scheduleLater.addEventListener('change', function() {
      if (this.checked) scheduledInputs.classList.remove('d-none');
    });
  }
  if (scheduleNow && scheduledInputs) {
    scheduleNow.addEventListener('change', function() {
      if (this.checked) scheduledInputs.classList.add('d-none');
    });
  }
});
</script>
@endpush
@endsection
