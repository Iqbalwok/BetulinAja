@extends('layouts.customer')

@section('title', 'Profil Akun Saya — BetulinAja')

@section('customer_content')
<div class="bg-light py-4 border-bottom">
  <div class="container">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-2 small">
        <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-decoration-none text-secondary">Beranda</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profil Pengguna</li>
      </ol>
    </nav>
    <h3 class="fw-bold text-dark mb-0">Pengaturan Profil & Alamat</h3>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">
    <!-- Profile Sidebar -->
    <div class="col-lg-4">
      <div class="card card-custom p-4 text-center mb-4">
        <div class="position-relative d-inline-block mx-auto mb-3">
          <img src="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80" alt="Ahmad Fauzi" class="rounded-circle object-fit-cover shadow-sm" style="width: 90px; height: 90px; border: 3px solid var(--primary-light);">
          <button type="button" class="btn btn-primary-custom btn-sm rounded-circle position-absolute bottom-0 end-0 p-1" style="width: 28px; height: 28px;" title="Ubah Foto">
            <i class="bi bi-camera-fill" style="font-size: 0.75rem;"></i>
          </button>
        </div>
        <h5 class="fw-bold text-dark mb-1">Ahmad Fauzi</h5>
        <span class="text-secondary small d-block mb-3">Pelanggan Setia &bull; Bergabung sejak Jan 2025</span>
        <div class="badge-soft-success py-1 px-3 small d-inline-flex align-items-center gap-1">
          <i class="bi bi-shield-check"></i> Akun Terverifikasi
        </div>

        <div class="pt-4 border-top mt-4 text-start">
          <div class="d-flex justify-content-between py-2 border-bottom small">
            <span class="text-secondary">Total Pesanan</span>
            <span class="fw-bold text-dark">4 Transaksi</span>
          </div>
          <div class="d-flex justify-content-between py-2 border-bottom small">
            <span class="text-secondary">Poin Reward</span>
            <span class="fw-bold text-primary">150 Poin</span>
          </div>
          <div class="d-flex justify-content-between py-2 small">
            <span class="text-secondary">Garansi Aktif</span>
            <span class="badge-soft-primary small">1 Layanan</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Main Form -->
    <div class="col-lg-8">
      <div class="card card-custom p-4 p-md-5 mb-4">
        <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Informasi Pribadi</h5>

        <form onsubmit="event.preventDefault(); BetulinAja.showToast('Profil Disimpan', 'Data profil Anda telah berhasil diperbarui.', 'success');">
          <div class="row g-3">
            <div class="col-sm-6">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" value="Ahmad Fauzi" required>
            </div>
            <div class="col-sm-6">
              <label class="form-label">Nomor WhatsApp / HP</label>
              <input type="tel" class="form-control" value="081298765432" required>
            </div>
            <div class="col-12">
              <label class="form-label">Alamat Email</label>
              <input type="email" class="form-control" value="ahmad.fauzi@example.com" required>
            </div>
          </div>

          <div class="mt-4 pt-3 border-top d-flex justify-content-end">
            <button type="submit" class="btn btn-primary-custom px-4">
              <i class="bi bi-check2 me-1"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>

      <!-- Saved Addresses -->
      <div class="card card-custom p-4 p-md-5">
        <div class="d-flex align-items-center justify-content-between mb-4 pb-2 border-bottom">
          <h5 class="fw-bold text-dark mb-0">Daftar Alamat Tersimpan</h5>
          <button type="button" class="btn btn-sm btn-outline-custom" onclick="BetulinAja.showToast('Tambah Alamat', 'Membuka form penambahan alamat baru...', 'info')">
            <i class="bi bi-plus-lg me-1"></i> Tambah Alamat
          </button>
        </div>

        <div class="border rounded-3 p-3 mb-3 bg-light position-relative">
          <div class="d-flex align-items-center gap-2 mb-1">
            <h6 class="fw-bold text-dark mb-0">Rumah Utama</h6>
            <span class="badge-soft-primary small py-0 px-2" style="font-size: 0.7rem;">Utama</span>
          </div>
          <p class="text-secondary small mb-1">Jl. Sudirman No. 45, RT 02/RW 04, Kebayoran Baru, Jakarta Selatan</p>
          <span class="text-muted small"><i class="bi bi-pin-map me-1 text-danger"></i>Patokan: Pagar hitam samping Alfamart</span>
        </div>

        <div class="border rounded-3 p-3">
          <div class="d-flex align-items-center gap-2 mb-1">
            <h6 class="fw-bold text-dark mb-0">Kantor Studio</h6>
          </div>
          <p class="text-secondary small mb-1">Jl. Gatot Subroto Kav. 18, Lantai 4, Kuningan Barat, Jakarta Selatan</p>
          <span class="text-muted small"><i class="bi bi-pin-map me-1 text-danger"></i>Patokan: Lobby Gedung B</span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
