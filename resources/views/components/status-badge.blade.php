@props(['status'])

@php
  $statusLower = strtolower($status ?? '');
  $badgeClass = 'badge-soft-secondary';
  $icon = 'bi-circle-fill';

  if (str_contains($statusLower, 'selesai')) {
    $badgeClass = 'badge-soft-success';
    $icon = 'bi-check-circle-fill';
  } elseif (str_contains($statusLower, 'batal')) {
    $badgeClass = 'badge-soft-danger';
    $icon = 'bi-x-circle-fill';
  } elseif (str_contains($statusLower, 'perjalanan') || str_contains($statusLower, 'ditemukan')) {
    $badgeClass = 'badge-soft-primary';
    $icon = 'bi-bicycle';
  } elseif (str_contains($statusLower, 'kerjakan') || str_contains($statusLower, 'proses') || str_contains($statusLower, 'menunggu')) {
    $badgeClass = 'badge-soft-warning';
    $icon = 'bi-clock-history';
  } elseif (str_contains($statusLower, 'cari')) {
    $badgeClass = 'badge-soft-info';
    $icon = 'bi-search';
  }
@endphp

<span class="{{ $badgeClass }} d-inline-flex align-items-center gap-1">
  <i class="bi {{ $icon }}" style="font-size: 0.75rem;"></i>
  <span>{{ $status }}</span>
</span>
