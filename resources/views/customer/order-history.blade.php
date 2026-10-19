@extends('layouts.customer')

@section('title', 'Riwayat Pesanan Saya — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pesanan Saya</li>
      </ol>
    </nav>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
      <div>
        <h3 class="fw-bold text-dark mb-1">Riwayat & Status Pesanan</h3>
        <p class="text-secondary small mb-0">Pantau pekerjaan yang sedang berlangsung dan arsip perbaikan rumah Anda.</p>
      </div>
      <a href="{{ url('/customer/booking') }}" class="btn btn-primary-custom text-nowrap">
        <i class="bi bi-plus-circle me-1"></i> Pesan Jasa Baru
      </a>
    </div>
  </div>
</div>

<div class="container py-5">
  <!-- Tabs Navigation -->
  <ul class="nav nav-pills mb-4 gap-2" id="order-history-tabs">
    <li class="nav-item">
      <button class="nav-link active rounded-pill px-4" data-status="all">Semua Pesanan (4)</button>
    </li>
    <li class="nav-item">
      <button class="nav-link rounded-pill px-4" data-status="active">Sedang Berjalan (1)</button>
    </li>
    <li class="nav-item">
      <button class="nav-link rounded-pill px-4" data-status="done">Selesai (2)</button>
    </li>
    <li class="nav-item">
      <button class="nav-link rounded-pill px-4" data-status="cancelled">Dibatalkan (1)</button>
    </li>
  </ul>

  <!-- Orders List -->
  <div class="order-list-wrapper">
    <!-- Active Order -->
    <div class="order-item-card" data-category="active">
      <x-order-card 
        orderNumber="ORD-849201"
        service="Cuci AC Standar (0.5 - 1 PK)"
        technician="Budi Santoso"
        address="Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan"
        price="Rp150.000"
        date="19 Okt 2026, 14:00 WIB"
        status="Dalam Perjalanan"
        role="customer"
      />
    </div>

    <!-- Completed Order 1 -->
    <div class="order-item-card" data-category="done">
      <x-order-card 
        orderNumber="ORD-781923"
        service="Perbaikan Korsleting MCB & Jalur Kabel"
        technician="I Made Agus"
        address="Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan"
        price="Rp145.000"
        date="12 Okt 2026, 10:30 WIB"
        status="Selesai"
        role="customer"
      />
    </div>

    <!-- Completed Order 2 -->
    <div class="order-item-card" data-category="done">
      <x-order-card 
        orderNumber="ORD-629104"
        service="Pelancaran Pipa Wastafel Cuci Piring Mampet"
        technician="Hendro Wijaya"
        address="Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan"
        price="Rp120.000"
        date="28 Sep 2026, 16:15 WIB"
        status="Selesai"
        role="customer"
      />
    </div>

    <!-- Cancelled Order -->
    <div class="order-item-card" data-category="cancelled">
      <x-order-card 
        orderNumber="ORD-510294"
        service="Service TV LED 43 Inch Layar Gelap"
        technician="Dedi Kurniawan"
        address="Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan"
        price="Rp150.000"
        date="15 Agu 2026, 13:00 WIB"
        status="Dibatalkan"
        role="customer"
      />
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const tabButtons = document.querySelectorAll('#order-history-tabs button');
  const orderCards = document.querySelectorAll('.order-item-card');

  tabButtons.forEach(btn => {
    btn.addEventListener('click', function() {
      tabButtons.forEach(b => b.classList.remove('active'));
      this.classList.add('active');

      const target = this.dataset.status;
      orderCards.forEach(card => {
        if (target === 'all' || card.dataset.category === target) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
});
</script>
@endpush
@endsection
