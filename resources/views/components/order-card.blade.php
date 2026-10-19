@props([
  'orderNumber' => 'ORD-849201',
  'service' => 'Cuci AC Standar (0.5 - 1 PK)',
  'technician' => 'Budi Santoso',
  'customer' => 'Ahmad Fauzi',
  'address' => 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
  'price' => 'Rp150.000',
  'date' => '19 Okt 2026, 14:00 WIB',
  'status' => 'Dalam Perjalanan',
  'role' => 'customer' // 'customer', 'partner', 'admin'
])

<div class="card card-custom card-hover p-4 mb-3 order-card-item">
  <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 pb-3 border-bottom">
    <div>
      <span class="fw-bold text-dark font-monospace me-2">{{ $orderNumber }}</span>
      <span class="text-secondary small"><i class="bi bi-calendar3 me-1"></i>{{ $date }}</span>
    </div>
    <div>
      <x-status-badge :status="$status" />
    </div>
  </div>

  <div class="row g-3 py-3 align-items-center">
    <div class="col-md-5">
      <h6 class="fw-bold text-dark mb-1">{{ $service }}</h6>
      <p class="text-secondary small mb-0 text-truncate">
        <i class="bi bi-geo-alt text-danger me-1"></i>{{ $address }}
      </p>
    </div>

    <div class="col-md-4">
      @if($role === 'customer')
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-person-gear text-primary fs-5"></i>
          <div>
            <span class="text-muted d-block" style="font-size: 0.75rem;">Teknisi Ditugaskan</span>
            <span class="fw-semibold text-dark small">{{ $technician }}</span>
          </div>
        </div>
      @else
        <div class="d-flex align-items-center gap-2">
          <i class="bi bi-person text-success fs-5"></i>
          <div>
            <span class="text-muted d-block" style="font-size: 0.75rem;">Pelanggan</span>
            <span class="fw-semibold text-dark small">{{ $customer }}</span>
          </div>
        </div>
      @endif
    </div>

    <div class="col-md-3 text-md-end">
      <span class="text-muted d-block" style="font-size: 0.75rem;">Total Biaya</span>
      <span class="fw-bold text-primary fs-5">{{ $price }}</span>
    </div>
  </div>

  <div class="pt-3 border-top d-flex flex-wrap align-items-center justify-content-between gap-2">
    @if($role === 'customer')
      <a href="{{ url('/customer/tracking?order=' . $orderNumber) }}" class="btn btn-soft-primary btn-sm">
        <i class="bi bi-geo-alt me-1"></i> Lacak Status Pesanan
      </a>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-custom btn-sm" onclick="BetulinAja.showToast('Bantuan CS', 'Menghubungi customer care untuk pesanan {{ $orderNumber }}', 'info')">
          <i class="bi bi-question-circle me-1"></i> Bantuan
        </button>
        <a href="{{ url('/customer/booking?reorder=' . $orderNumber) }}" class="btn btn-primary-custom btn-sm">
          <i class="bi bi-arrow-repeat me-1"></i> Pesan Lagi
        </a>
      </div>
    @elseif($role === 'partner')
      <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-accent-green btn-sm btn-accept-order" data-id="{{ $orderNumber }}">
          <i class="bi bi-check-lg me-1"></i> Terima Pesanan
        </button>
        <button type="button" class="btn btn-outline-danger btn-sm btn-reject-order" data-id="{{ $orderNumber }}">
          <i class="bi bi-x-lg me-1"></i> Tolak
        </button>
      </div>
      <a href="{{ url('/partner/order-detail?order=' . $orderNumber) }}" class="btn btn-outline-custom btn-sm">
        Lihat Detail <i class="bi bi-arrow-right ms-1"></i>
      </a>
    @else
      <span class="small text-muted">ID Pesanan Sistem: #{{ substr(md5($orderNumber), 0, 8) }}</span>
      <button type="button" class="btn btn-outline-custom btn-sm" data-bs-toggle="modal" data-bs-target="#adminOrderDetailModal">
        Kelola Pesanan <i class="bi bi-arrow-right ms-1"></i>
      </button>
    @endif
  </div>
</div>
