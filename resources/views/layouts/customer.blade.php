@extends('layouts.app')

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/customer.css') }}">
@endpush

@section('content')
  <!-- Customer Navbar -->
  @include('components.navbar')

  <!-- Main Customer Content -->
  <main class="flex-grow-1">
    @yield('customer_content')
  </main>

  <!-- Customer Footer -->
  @include('components.footer')
@endsection

@push('scripts')
  <script src="{{ asset('assets/js/customer.js') }}"></script>
@endpush
