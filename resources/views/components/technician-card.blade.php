@props([
  'id' => 1,
  'name' => 'Budi Santoso',
  'specialty' => 'Teknisi AC & Pendingin',
  'rating' => 4.9,
  'reviews' => 142,
  'jobs' => 280,
  'distance' => '2.5 km',
  'available' => true,
  'photo' => 'https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=200&auto=format&fit=crop&q=80',
  'selectMode' => false
])

<div class="card card-custom card-hover h-100 p-3 p-md-4 d-flex flex-column justify-content-between">
  <div>
    <div class="d-flex align-items-center gap-3 mb-3">
      <div class="position-relative flex-shrink-0">
        <img src="{{ $photo }}" alt="{{ $name }}" class="rounded-circle object-fit-cover shadow-sm" style="width: 60px; height: 60px; border: 2px solid var(--border-color);">
        @if($available)
          <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" title="Tersedia Sekarang" style="width: 14px; height: 14px;"></span>
        @endif
      </div>
      <div>
        <div class="d-flex align-items-center gap-1">
          <h6 class="fw-bold text-dark mb-0 fs-6">{{ $name }}</h6>
          <i class="bi bi-patch-check-fill text-primary" title="Mitra Terverifikasi"></i>
        </div>
        <span class="text-secondary small d-block mb-1">{{ $specialty }}</span>
        <div class="d-flex align-items-center gap-2" style="font-size: 0.8rem;">
          <span class="text-warning fw-bold d-flex align-items-center gap-1">
            <i class="bi bi-star-fill"></i> {{ $rating }}
          </span>
          <span class="text-muted">({{ $reviews }} ulasan)</span>
        </div>
      </div>
    </div>

    <div class="bg-light rounded-3 p-2 mb-3 d-flex align-items-center justify-content-around text-center" style="font-size: 0.8rem;">
      <div>
        <span class="text-muted d-block" style="font-size: 0.72rem;">Selesai</span>
        <span class="fw-bold text-dark">{{ $jobs }}+ Order</span>
      </div>
      <div class="border-start border-secondary-subtle ps-2">
        <span class="text-muted d-block" style="font-size: 0.72rem;">Jarak</span>
        <span class="fw-bold text-primary">{{ $distance }}</span>
      </div>
      <div class="border-start border-secondary-subtle ps-2">
        <span class="text-muted d-block" style="font-size: 0.72rem;">Status</span>
        <span class="fw-bold text-success">{{ $available ? 'Tersedia' : 'Sibuk' }}</span>
      </div>
    </div>
  </div>

  <div class="d-flex align-items-center gap-2 pt-2 border-top">
    @if($selectMode)
      <button type="button" class="btn btn-outline-custom btn-sm flex-fill" data-bs-toggle="modal" data-bs-target="#technicianDetailModal" data-name="{{ $name }}">
        Detail Profil
      </button>
      <button type="button" class="btn btn-primary-custom btn-sm flex-fill select-tech-btn" data-id="{{ $id }}" data-name="{{ $name }}">
        Pilih
      </button>
    @else
      <a href="{{ url('/customer/technicians?id=' . $id) }}" class="btn btn-outline-custom btn-sm flex-fill">
        Lihat Profil
      </a>
      <a href="{{ url('/customer/booking?tech_id=' . $id) }}" class="btn btn-primary-custom btn-sm flex-fill">
        Pesan Jasa
      </a>
    @endif
  </div>
</div>
