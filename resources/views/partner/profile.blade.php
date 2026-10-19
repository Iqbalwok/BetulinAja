@extends('layouts.partner')

@section('title', 'Profil & Pengaturan Mitra — BetulinAja')
@section('page_title', 'Profil Teknisi & Dokumen Verifikasi')

@section('partner_content')
<div class="row g-4">
  <!-- Profile Summary -->
  <div class="col-lg-4">
    <div class="card card-custom p-4 text-center mb-4">
      <div class="position-relative d-inline-block mx-auto mb-3">
        <img src="https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=160&auto=format&fit=crop&q=80" alt="Budi Santoso" class="rounded-circle object-fit-cover shadow" style="width: 100px; height: 100px; border: 3px solid var(--accent-green);">
        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle p-1 text-white" style="width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
          <i class="bi bi-patch-check-fill" style="font-size: 0.9rem;"></i>
        </span>
      </div>

      <h5 class="fw-bold text-dark mb-1">Budi Santoso</h5>
      <span class="text-secondary small d-block mb-3">ID Mitra: #MITRA-8291 &bull; Bergabung 2024</span>

      <div class="badge-soft-success py-1 px-3 small d-inline-flex align-items-center gap-1 mb-3">
        <i class="bi bi-shield-check"></i> Mitra Terverifikasi Tingkat Emas
      </div>

      <div class="bg-light rounded-3 p-3 text-start small">
        <div class="d-flex justify-content-between py-1 border-bottom">
          <span class="text-secondary">Rating Rata-rata</span>
          <span class="fw-bold text-warning"><i class="bi bi-star-fill me-1"></i>4.9 / 5.0</span>
        </div>
        <div class="d-flex justify-content-between py-1 border-bottom">
          <span class="text-secondary">Pekerjaan Sukses</span>
          <span class="fw-bold text-dark">284 Order</span>
        </div>
        <div class="d-flex justify-content-between pt-1">
          <span class="text-secondary">Tingkat Penyelesaian</span>
          <span class="fw-bold text-success">98.5%</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Profile Form & Settings -->
  <div class="col-lg-8">
    <div class="card card-custom p-4 p-md-5 mb-4">
      <h5 class="fw-bold text-dark mb-4 pb-2 border-bottom">Data Pribadi & Keahlian</h5>

      <form onsubmit="event.preventDefault(); BetulinAja.showToast('Perubahan Disimpan', 'Data profil teknisi berhasil disimpan.', 'success');">
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Nama Lengkap Sesuai KTP</label>
            <input type="text" class="form-control" value="Budi Santoso" required>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Nomor WhatsApp Aktif</label>
            <input type="tel" class="form-control" value="081234567890" required>
          </div>
          <div class="col-12">
            <label class="form-label">Keahlian & Sertifikasi Utama</label>
            <input type="text" class="form-control" value="Service AC Split, Inverter, Cuci AC, Tambah Freon R32, Pompa Air Jet Pump">
            <div class="form-text text-muted">Pisahkan keahlian dengan tanda koma.</div>
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-3">Wilayah Layanan & Kendaraan</h6>
        <div class="row g-3 mb-4">
          <div class="col-sm-6">
            <label class="form-label">Radius Jangkauan Operasional</label>
            <select class="form-select">
              <option>Maksimal 5 km</option>
              <option selected>Maksimal 10 km (Standar)</option>
              <option>Maksimal 15 km</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label class="form-label">Kendaraan Operasional</label>
            <input type="text" class="form-control" value="Sepeda Motor Honda Vario (B 1234 XYZ)">
          </div>
        </div>

        <div class="pt-3 border-top d-flex justify-content-end">
          <button type="submit" class="btn btn-primary-custom px-4">
            <i class="bi bi-save me-1"></i> Simpan Profil Mitra
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
