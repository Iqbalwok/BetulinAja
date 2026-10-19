@props([
  'icon' => 'bi-tools',
  'title' => 'Service AC Standar',
  'category' => 'Service AC',
  'price' => 'Rp100.000',
  'description' => 'Pembersihan evaporator, filter udara, dan pengecekan tekanan freon.',
  'badge' => null
])

<div class="card card-custom card-hover h-100 p-4 d-flex flex-column justify-content-between">
  <div>
    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="category-icon-box m-0">
        <i class="bi {{ $icon }}"></i>
      </div>
      @if($badge)
        <span class="badge-soft-primary small">{{ $badge }}</span>
      @else
        <span class="badge-soft-secondary small">{{ $category }}</span>
      @endif
    </div>

    <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
    <p class="text-secondary small mb-3" style="line-height: 1.5;">
      {{ $description }}
    </p>
  </div>

  <div class="pt-3 border-top d-flex align-items-center justify-content-between mt-auto">
    <div>
      <span class="text-muted small d-block" style="font-size: 0.75rem;">Mulai dari</span>
      <span class="fw-bold text-primary fs-6">{{ $price }}</span>
    </div>
    <a href="{{ url('/customer/booking?cat=' . urlencode($category) . '&srv=' . urlencode($title)) }}" class="btn btn-soft-primary btn-sm px-3 py-2">
      Pesan <i class="bi bi-chevron-right ms-1"></i>
    </a>
  </div>
</div>
