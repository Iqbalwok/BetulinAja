@extends('layouts.customer')

@section('title', 'Cakupan Wilayah Layanan — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wilayah Layanan</li>
      </ol>
    </nav>
    <h3 class="fw-bold text-dark mb-1">Cakupan Wilayah & Jangkauan Teknisi</h3>
    <p class="text-secondary small mb-0">Teknisi BetulinAja siap melayani perbaikan di seluruh area Jabodetabek.</p>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4 mb-4">
    <!-- Map Container -->
    <div class="col-lg-8">
      <div class="card card-custom p-0 overflow-hidden shadow-sm">
        <div class="p-3 bg-white border-bottom d-flex align-items-center justify-content-between">
          <span class="fw-bold text-dark small"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Peta Jangkauan & Zona Layanan</span>
          <span class="badge-soft-success small">850+ Mitra Siaga</span>
        </div>
        <div id="coverage-map" style="height: 420px; width: 100%;"></div>
      </div>
    </div>

    <!-- Quick Check Box -->
    <div class="col-lg-4">
      <div class="card card-custom p-4 h-100 d-flex flex-column justify-content-between">
        <div>
          <h5 class="fw-bold text-dark mb-3">Cek Ketersediaan Area</h5>
          <p class="text-secondary small mb-4">Masukkan kecamatan atau nama kelurahan untuk memastikan radius jangkauan teknisi.</p>

          <div class="mb-3">
            <label class="form-label">Kecamatan / Kota Anda</label>
            <input type="text" class="form-control" id="area-check-input" placeholder="Contoh: Kebayoran Baru, Serpong, Bekasi...">
          </div>

          <button type="button" class="btn btn-primary-custom w-100 mb-4" id="btn-check-area">
            <i class="bi bi-search me-1"></i> Periksa Area
          </button>

          <h6 class="fw-bold text-dark small mb-2">Wilayah Operasional Aktif:</h6>
          <ul class="list-unstyled small text-secondary d-flex flex-column gap-2 mb-0">
            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Jakarta:</strong> Selatan, Barat, Pusat, Timur, Utara</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Tangerang:</strong> Kota & Tangerang Selatan (BSD, Bintaro)</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Bekasi:</strong> Barat, Timur, Tambun & sekitarnya</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Depok:</strong> Margonda, Cinere, Sawangan</li>
            <li><i class="bi bi-check-circle-fill text-success me-2"></i><strong>Bogor:</strong> Kota & Cibinong</li>
          </ul>
        </div>

        <div class="pt-4 border-top mt-4">
          <a href="{{ url('/customer/booking') }}" class="btn btn-accent-green w-100">
            <i class="bi bi-plus-circle me-1"></i> Pesan Teknisi di Area Saya
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const mapEl = document.getElementById('coverage-map');
  if (!mapEl || typeof L === 'undefined') return;

  const map = L.map('coverage-map').setView([-6.2088, 106.8456], 11);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  // Coverage zones
  const zones = [
    { name: 'Zona Jakarta Selatan & Pusat', lat: -6.2297, lng: 106.8164, radius: 8000, techs: '240 Teknisi' },
    { name: 'Zona Tangerang Selatan (BSD/Bintaro)', lat: -6.2900, lng: 106.7000, radius: 7000, techs: '180 Teknisi' },
    { name: 'Zona Bekasi', lat: -6.2383, lng: 106.9756, radius: 7500, techs: '150 Teknisi' },
    { name: 'Zona Depok', lat: -6.4025, lng: 106.7942, radius: 6500, techs: '110 Teknisi' }
  ];

  zones.forEach(z => {
    L.circle([z.lat, z.lng], {
      color: '#2563EB',
      fillColor: '#2563EB',
      fillOpacity: 0.15,
      radius: z.radius
    }).addTo(map).bindPopup(`<b>${z.name}</b><br>Ketersediaan: ${z.techs}`);
  });

  const btnCheck = document.getElementById('btn-check-area');
  const inputCheck = document.getElementById('area-check-input');
  if (btnCheck && inputCheck) {
    btnCheck.addEventListener('click', function() {
      const val = inputCheck.value.trim();
      if (!val) {
        BetulinAja.showToast('Input Kosong', 'Silakan ketikkan nama kecamatan atau kota Anda.', 'warning');
        return;
      }
      BetulinAja.showToast('Area Tercover!', `Kabar baik! Wilayah "${val}" berada di dalam zona layanan aktif teknisi BetulinAja.`, 'success');
    });
  }
});
</script>
@endpush
@endsection
