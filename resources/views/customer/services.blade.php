@extends('layouts.customer')

@section('title', 'Katalog Layanan Service — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Layanan</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <h2 class="fw-bold text-dark mb-1">Daftar Lengkap Layanan Service</h2>
        <p class="text-secondary small mb-0">Temukan solusi tepat untuk segala perbaikan perlengkapan rumah Anda.</p>
      </div>
      <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom text-nowrap">
        <i class="bi bi-calendar-plus me-1"></i> Buat Pesanan Baru
      </a>
    </div>
  </div>
</div>

<div class="container py-5">
  <!-- Search & Category Filters -->
  <div class="row g-3 mb-4 align-items-center">
    <div class="col-md-5">
      <div class="input-group">
        <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
        <input type="text" id="service-search-input" class="form-control border-start-0" placeholder="Ketik jenis perbaikan (contoh: Freon, MCB, Pompa)...">
      </div>
    </div>
    <div class="col-md-7">
      <div class="d-flex gap-2 overflow-x-auto pb-2 pb-md-0" id="service-filter-pills">
        <button type="button" class="btn btn-sm btn-primary-custom filter-pill active" data-category="all">Semua</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="ac">Service AC</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="elektronik">Elektronik</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="listrik">Listrik</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="plumbing">Plumbing</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="kulkas">Kulkas</button>
        <button type="button" class="btn btn-sm btn-outline-custom filter-pill" data-category="komputer">Komputer</button>
      </div>
    </div>
  </div>

  <!-- Services Grid -->
  <div class="row g-4" id="services-grid-container">
    <!-- Service AC -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="ac">
      <x-service-card 
        icon="bi-snow"
        title="Cuci AC Standar (0.5 - 1 PK)"
        category="Service AC"
        price="Rp100.000"
        description="Pembersihan unit indoor, filter debu, evaporator, baling-baling blower, serta drainase pembuangan air."
        badge="Populer"
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="ac">
      <x-service-card 
        icon="bi-snow2"
        title="Isi / Tambah Freon R32 & R410"
        category="Service AC"
        price="Rp175.000"
        description="Pemeriksaan tekanan manifold gauge, penambahan freon ramah lingkungan, dan uji kebocoran nepel pipa."
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="ac">
      <x-service-card 
        icon="bi-arrow-left-right"
        title="Bongkar Pasang AC Split"
        category="Service AC"
        price="Rp250.000"
        description="Pencopotan unit lama, pemindahan jalur pipa tembaga, pemasangan bracket, dan vakum sistem pipa."
      />
    </div>

    <!-- Elektronik -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="elektronik">
      <x-service-card 
        icon="bi-tv"
        title="Service TV LED / Smart TV"
        category="Elektronik"
        price="Rp150.000"
        description="Pengecekan modul power supply, backlight mati, layar bergaris, motherboard dan penggantian elco/kapasitor."
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="elektronik">
      <x-service-card 
        icon="bi-circle-square"
        title="Perbaikan Mesin Cuci"
        category="Elektronik"
        price="Rp130.000"
        description="Penanganan dinamo motor macet, air tidak mau keluar/mengisi, pulsator tidak berputar, dan modul eror."
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="elektronik">
      <x-service-card 
        icon="bi-motherboard"
        title="Service Microwave & Oven Listrik"
        category="Elektronik"
        price="Rp110.000"
        description="Perbaikan magnetron tidak memanaskan makanan, saklar pintu error, piring putar macet, dan sekring putus."
      />
    </div>

    <!-- Listrik -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="listrik">
      <x-service-card 
        icon="bi-lightning-charge"
        title="Perbaikan Korsleting / MCB Sering Turun"
        category="Listrik"
        price="Rp125.000"
        description="Tracing jalur kabel yang bergesekan, penggantian MCB kelebihan beban, dan pembersihan instalasi grounding."
        badge="Prioritas Keamanan"
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="listrik">
      <x-service-card 
        icon="bi-toggle-on"
        title="Pemasangan Stop Kontak & Saklar"
        category="Listrik"
        price="Rp75.000"
        description="Penambahan titik stop kontak baru, saklar hotel (dua arah), dan perapian kabel jalur luar dinding."
      />
    </div>

    <!-- Plumbing -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="plumbing">
      <x-service-card 
        icon="bi-droplet-half"
        title="Pelancaran Saluran Air Mampet"
        category="Plumbing"
        price="Rp120.000"
        description="Penanganan pipa bak cuci piring mampet lemak, floor drain kamar mandi tersumbat tanpa bongkar lantai."
        badge="Bergaransi"
      />
    </div>

    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="plumbing">
      <x-service-card 
        icon="bi-water"
        title="Service Pompa Air & Jet Pump"
        category="Plumbing"
        price="Rp160.000"
        description="Perbaikan otomatis pompa mendadak cetak-cetek, impeller aus, ganti seal mechanical, dan pancingan angin."
      />
    </div>

    <!-- Kulkas -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="kulkas">
      <x-service-card 
        icon="bi-box-seam"
        title="Service Kulkas 1 / 2 Pintu Tidak Dingin"
        category="Kulkas"
        price="Rp140.000"
        description="Pengecekan timer defrost, bimetal heater, kipas evaporator, dan pembersihan kisi-kisi kondensor."
      />
    </div>

    <!-- Komputer -->
    <div class="col-md-6 col-lg-4 service-grid-item" data-cat="komputer">
      <x-service-card 
        icon="bi-laptop"
        title="Pembersihan Thermal Paste & Fan Laptop"
        category="Komputer"
        price="Rp90.000"
        description="Pembersihan debu heatsink pendingin, ganti thermal paste premium (Artic MX-4), agar suhu tidak overheat."
      />
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const pills = document.querySelectorAll('.filter-pill');
  const items = document.querySelectorAll('.service-grid-item');
  const searchInput = document.getElementById('service-search-input');

  // Handle URL param filter if present
  const urlParams = new URLSearchParams(window.location.search);
  const catParam = urlParams.get('cat');
  if (catParam) {
    pills.forEach(p => {
      if (p.dataset.category.toLowerCase() === catParam.toLowerCase()) {
        p.click();
      }
    });
  }

  pills.forEach(pill => {
    pill.addEventListener('click', function() {
      pills.forEach(p => {
        p.classList.remove('btn-primary-custom', 'active');
        p.classList.add('btn-outline-custom');
      });
      this.classList.add('btn-primary-custom', 'active');
      this.classList.remove('btn-outline-custom');

      const cat = this.dataset.category;
      items.forEach(item => {
        if (cat === 'all' || item.dataset.cat === cat) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      const q = this.value.toLowerCase();
      items.forEach(item => {
        const text = item.textContent.toLowerCase();
        item.style.display = text.includes(q) ? '' : 'none';
      });
    });
  }
});
</script>
@endpush
@endsection
