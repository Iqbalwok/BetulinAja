@extends('layouts.admin')

@section('title', 'Pengaturan Sistem — Admin BetulinAja')
@section('page_title', 'Konfigurasi Sistem & Pengaturan Platform')

@section('admin_content')
<div class="row g-4">
  <div class="col-lg-8">
    <div class="card card-custom p-4 p-md-5 mb-4">
      <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Pengaturan Operasional & Marketplace</h5>

      <form onsubmit="event.preventDefault(); BetulinAja.showToast('Pengaturan Disimpan', 'Konfigurasi platform berhasil diperbarui.', 'success');">
        <!-- Brand Info -->
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-building text-primary me-2"></i>Identitas Brand & Platform</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Nama Aplikasi</label>
            <input type="text" class="form-control" value="BetulinAja">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Tagline Resmi</label>
            <input type="text" class="form-control" value="Semua Masalah Rumah, BetulinAja!">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Email Customer Care</label>
            <input type="email" class="form-control" value="bantuan@betulinaja.id">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Nomor WhatsApp CS</label>
            <input type="tel" class="form-control" value="081234567890">
          </div>
        </div>

        <!-- Rules & Commission -->
        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-percent text-success me-2"></i>Komisi & Aturan Penugasan</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Persentase Fee Platform (%)</label>
            <input type="number" class="form-control" value="15" min="0" max="50">
            <div class="form-text text-muted">Mitra menerima 85% dari total nilai jasa.</div>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Batas Waktu Respon Order Mitra (Detik)</label>
            <input type="number" class="form-control" value="60" min="15" max="300">
            <div class="form-text text-muted">Waktu sebelum order dialihkan otomatis ke mitra lain.</div>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Durasi Garansi Layanan (Hari)</label>
            <input type="number" class="form-control" value="30">
          </div>
          <div class="col-sm-6">
            <label class="form-label">Masa Aktif Peringatan Komplain (Jam)</label>
            <input type="number" class="form-control" value="48">
          </div>
        </div>

        <div class="pt-3 border-top d-flex justify-content-end">
          <button type="submit" class="btn btn-primary-custom px-4">
            <i class="bi bi-save me-1"></i> Simpan Konfigurasi
          </button>
        </div>
      </form>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card card-custom p-4 mb-4">
      <h6 class="fw-bold text-dark mb-3">Status Lingkungan Server</h6>
      <div class="d-flex justify-content-between py-2 border-bottom small">
        <span class="text-secondary">Framework</span>
        <span class="fw-bold text-dark">Laravel 12 (Blade Engine)</span>
      </div>
      <div class="d-flex justify-content-between py-2 border-bottom small">
        <span class="text-secondary">Versi PHP</span>
        <span class="fw-bold text-dark">8.2.12</span>
      </div>
      <div class="d-flex justify-content-between py-2 border-bottom small">
        <span class="text-secondary">Arsitektur Mode</span>
        <span class="badge-soft-primary small">Frontend Only (Prototype)</span>
      </div>
      <div class="d-flex justify-content-between py-2 small">
        <span class="text-secondary">Database Backend</span>
        <span class="text-muted">Siap Integrasi MySQL</span>
      </div>
    </div>
  </div>
</div>
@endsection
