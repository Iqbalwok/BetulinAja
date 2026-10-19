@extends('layouts.customer')

@section('title', 'Direktori Mitra Teknisi Terverifikasi — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Mitra Teknisi</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <h3 class="fw-bold text-dark mb-1">Mitra Teknisi Profesional</h3>
        <p class="text-secondary small mb-0">Semua teknisi telah melewati verifikasi identitas, sertifikasi keahlian, dan evaluasi berkala.</p>
      </div>
      <a href="{{ url('/partner/login') }}" class="btn btn-outline-custom text-nowrap">
        <i class="bi bi-person-plus me-1"></i> Gabung Jadi Mitra
      </a>
    </div>
  </div>
</div>

<div class="container py-5">
  <!-- Search & Filter Bar -->
  <div class="card card-custom p-3 p-md-4 mb-4">
    <div class="row g-3 align-items-center">
      <div class="col-md-5">
        <div class="input-group">
          <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
          <input type="text" id="tech-search-input" class="form-control border-start-0" placeholder="Cari nama teknisi atau keahlian...">
        </div>
      </div>
      <div class="col-md-4">
        <select class="form-select" id="tech-specialty-filter">
          <option value="all" selected>Semua Spesialisasi</option>
          <option value="ac">Teknisi AC & Pendingin</option>
          <option value="listrik">Instalasi Listrik & Panel</option>
          <option value="plumbing">Plumbing & Saluran Pipa</option>
          <option value="elektronik">Elektronik & Mesin Cuci</option>
        </select>
      </div>
      <div class="col-md-3">
        <select class="form-select" id="tech-rating-filter">
          <option value="all" selected>Semua Rating</option>
          <option value="4.9">Bintang 4.9 Ke Atas</option>
          <option value="4.8">Bintang 4.8 Ke Atas</option>
        </select>
      </div>
    </div>
  </div>

  <!-- Technicians Grid -->
  <div class="row g-4" id="tech-grid">
    <div class="col-md-6 col-lg-4 tech-item" data-specialty="ac" data-rating="4.9">
      <x-technician-card 
        id="1"
        name="Budi Santoso"
        specialty="Teknisi AC & Pendingin"
        rating="4.9"
        reviews="142"
        jobs="284"
        distance="2.4 km"
        :available="true"
        photo="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=200&auto=format&fit=crop&q=80"
      />
    </div>

    <div class="col-md-6 col-lg-4 tech-item" data-specialty="listrik" data-rating="4.8">
      <x-technician-card 
        id="2"
        name="I Made Agus"
        specialty="Master Kelistrikan & Panel"
        rating="4.8"
        reviews="98"
        jobs="192"
        distance="3.1 km"
        :available="true"
        photo="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80"
      />
    </div>

    <div class="col-md-6 col-lg-4 tech-item" data-specialty="plumbing" data-rating="5.0">
      <x-technician-card 
        id="3"
        name="Hendro Wijaya"
        specialty="Ahli Plumbing & Saluran Pipa"
        rating="5.0"
        reviews="165"
        jobs="310"
        distance="1.8 km"
        :available="true"
        photo="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&auto=format&fit=crop&q=80"
      />
    </div>

    <div class="col-md-6 col-lg-4 tech-item" data-specialty="elektronik" data-rating="4.8">
      <x-technician-card 
        id="4"
        name="Dedi Kurniawan"
        specialty="Service TV & Elektronik"
        rating="4.8"
        reviews="87"
        jobs="145"
        distance="4.2 km"
        :available="false"
        photo="https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=200&auto=format&fit=crop&q=80"
      />
    </div>

    <div class="col-md-6 col-lg-4 tech-item" data-specialty="ac" data-rating="4.9">
      <x-technician-card 
        id="5"
        name="Rian Firmansyah"
        specialty="Teknisi AC Split & Standing"
        rating="4.9"
        reviews="112"
        jobs="230"
        distance="3.7 km"
        :available="true"
        photo="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=200&auto=format&fit=crop&q=80"
      />
    </div>

    <div class="col-md-6 col-lg-4 tech-item" data-specialty="listrik" data-rating="4.7">
      <x-technician-card 
        id="6"
        name="Arief Prasetyo"
        specialty="Kelistrikan & Pompa Air"
        rating="4.7"
        reviews="76"
        jobs="118"
        distance="5.0 km"
        :available="true"
        photo="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80"
      />
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('tech-search-input');
  const specialtyFilter = document.getElementById('tech-specialty-filter');
  const items = document.querySelectorAll('.tech-item');

  function filterTechs() {
    const q = searchInput.value.toLowerCase();
    const spec = specialtyFilter.value;

    items.forEach(item => {
      const text = item.textContent.toLowerCase();
      const itemSpec = item.dataset.specialty;
      const matchesSearch = text.includes(q);
      const matchesSpec = spec === 'all' || itemSpec === spec;

      if (matchesSearch && matchesSpec) {
        item.style.display = '';
      } else {
        item.style.display = 'none';
      }
    });
  }

  searchInput.addEventListener('keyup', filterTechs);
  specialtyFilter.addEventListener('change', filterTechs);
});
</script>
@endpush
@endsection
