@extends('layouts.app')

@section('title', 'Login Mitra Teknisi — BetulinAja')

@section('content')
<div class="min-vh-100 d-flex flex-column justify-content-between bg-light">
  <!-- Minimal Top Header -->
  <header class="p-3 p-md-4 border-bottom bg-white">
    <div class="container d-flex align-items-center justify-content-between">
      <a class="navbar-brand-logo" href="{{ url('/') }}">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-3 p-1 me-1" style="width: 32px; height: 32px;">
          <i class="bi bi-tools fs-6"></i>
        </div>
        <span>Betulin<span class="brand-highlight">Aja</span></span>
      </a>
      <a href="{{ url('/') }}" class="btn btn-outline-custom btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Ke Website Utama
      </a>
    </div>
  </header>

  <!-- Login Card Form -->
  <main class="my-auto py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
          <div class="card card-custom p-4 p-md-5 border-0 shadow">
            <div class="text-center mb-4">
              <span class="badge-soft-success small mb-2 d-inline-block px-3 py-1">Portal Mitra Teknisi</span>
              <h4 class="fw-bold text-dark mb-1">Masuk ke Dashboard Teknisi</h4>
              <p class="text-secondary small">Kelola pesanan masuk dan pantau penghasilan harian Anda.</p>
            </div>

            <form onsubmit="event.preventDefault(); window.location.href = '{{ url('/partner/dashboard') }}';">
              <div class="mb-3">
                <label class="form-label">Nomor HP / WhatsApp Terdaftar</label>
                <div class="input-group">
                  <span class="input-group-text bg-light text-muted border-end-0">+62</span>
                  <input type="tel" class="form-control border-start-0" value="81234567890" placeholder="812-xxxx-xxxx" required>
                </div>
              </div>

              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-1">
                  <label class="form-label mb-0">PIN Keamanan / Sandi</label>
                  <a href="#" class="text-decoration-none small text-primary" onclick="alert('Demo prototype: Gunakan sandi apa saja untuk login demo.')">Lupa PIN?</a>
                </div>
                <input type="password" class="form-control" value="123456" placeholder="Masukkan 6 digit PIN" required>
              </div>

              <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="remember-me" checked>
                <label class="form-check-label text-secondary small" for="remember-me">
                  Ingat saya di perangkat ini
                </label>
              </div>

              <button type="submit" class="btn btn-primary-custom w-100 py-3 mb-3">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Sekarang (Demo)
              </button>

              <!-- Demo One-Click Access -->
              <div class="alert alert-info border-0 rounded-3 small text-secondary p-3 mb-0">
                <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-primary me-1"></i> Mode Demo Frontend</div>
                Form ini siap digunakan. Klik <strong>Masuk Sekarang</strong> untuk langsung mengakses Dashboard Mitra Budi Santoso.
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Minimal Footer -->
  <footer class="p-3 text-center text-secondary small border-top bg-white">
    &copy; {{ date('Y') }} BetulinAja Inc. Portal Mitra Teknisi Resmi.
  </footer>
</div>
@endsection
