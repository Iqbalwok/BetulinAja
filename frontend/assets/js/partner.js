/**
 * BetulinAja — Partner (Mitra Teknisi) JavaScript
 * Online/offline toggle, order acceptance workflow, active job step simulator,
 * before & after photo uploads, and earnings filter.
 */

document.addEventListener('DOMContentLoaded', function() {
  initPartnerOnlineToggle();
  initOrderActions();
  initActiveJobFlow();
});

// 1. Partner Online/Offline Status Toggle
function initPartnerOnlineToggle() {
  const toggleSwitch = document.getElementById('partner-status-toggle');
  const statusLabel = document.getElementById('partner-status-text');
  const statusDot = document.getElementById('partner-status-dot');

  if (toggleSwitch && statusLabel) {
    toggleSwitch.addEventListener('change', function() {
      if (this.checked) {
        statusLabel.textContent = 'Online — Siap Terima Order';
        statusLabel.className = 'fw-bold text-success small';
        if (statusDot) {
          statusDot.className = 'status-dot online';
        }
        BetulinAja.showToast('Status Aktif', 'Anda sekarang ONLINE. Pesanan masuk baru akan diarahkan ke Anda.', 'success');
      } else {
        statusLabel.textContent = 'Offline — Istirahat';
        statusLabel.className = 'fw-bold text-secondary small';
        if (statusDot) {
          statusDot.className = 'status-dot offline';
        }
        BetulinAja.showToast('Status Nonaktif', 'Anda sekarang OFFLINE. Pesanan baru dijeda sementara.', 'warning');
      }
    });
  }
}

// 2. Accept & Reject Order Buttons
function initOrderActions() {
  document.querySelectorAll('.btn-accept-order').forEach(btn => {
    btn.addEventListener('click', function() {
      const orderCard = this.closest('.order-card-item');
      const orderId = this.dataset.id || 'ORD-892134';
      BetulinAja.showToast('Pesanan Diterima!', `Pesanan ${orderId} telah Anda terima. Membuka detail pekerjaan...`, 'success');
      setTimeout(() => {
        window.location.href = '/partner/active-job?order=' + orderId;
      }, 1000);
    });
  });

  document.querySelectorAll('.btn-reject-order').forEach(btn => {
    btn.addEventListener('click', function() {
      const orderCard = this.closest('.order-card-item');
      const orderId = this.dataset.id || 'ORD-892134';
      if (confirm(`Apakah Anda yakin ingin menolak pesanan ${orderId}? Pesanan akan diteruskan ke teknisi lain.`)) {
        if (orderCard) {
          orderCard.style.transition = 'all 0.3s ease';
          orderCard.style.opacity = '0';
          orderCard.style.transform = 'scale(0.95)';
          setTimeout(() => orderCard.remove(), 300);
        }
        BetulinAja.showToast('Pesanan Ditolak', `Pesanan ${orderId} dialihkan ke teknisi lain.`, 'warning');
      }
    });
  });
}

// 3. Active Job Execution Flow
function initActiveJobFlow() {
  const btnStartTravel = document.getElementById('btn-start-travel');
  const btnArrived = document.getElementById('btn-arrived-location');
  const btnStartJob = document.getElementById('btn-start-job');
  const btnCompleteJob = document.getElementById('btn-complete-job');
  const currentStatusBadge = document.getElementById('active-job-status-badge');

  if (btnStartTravel) {
    btnStartTravel.addEventListener('click', function() {
      this.classList.add('d-none');
      if (btnArrived) btnArrived.classList.remove('d-none');
      if (currentStatusBadge) {
        currentStatusBadge.className = 'badge-soft-primary px-3 py-2';
        currentStatusBadge.innerHTML = '<i class="bi bi-bicycle me-1"></i> Dalam Perjalanan ke Lokasi';
      }
      BetulinAja.showToast('Perjalanan Dimulai', 'Status pelanggan telah diperbarui menjadi "Teknisi Menuju Lokasi". Hati-hati di jalan!', 'info');
    });
  }

  if (btnArrived) {
    btnArrived.addEventListener('click', function() {
      this.classList.add('d-none');
      if (btnStartJob) btnStartJob.classList.remove('d-none');
      if (currentStatusBadge) {
        currentStatusBadge.className = 'badge-soft-info px-3 py-2';
        currentStatusBadge.innerHTML = '<i class="bi bi-geo-alt-fill me-1"></i> Tiba di Lokasi Pelanggan';
      }
      BetulinAja.showToast('Tiba di Lokasi', 'Pelanggan mendapatkan notifikasi bahwa Anda telah tiba.', 'success');
    });
  }

  if (btnStartJob) {
    btnStartJob.addEventListener('click', function() {
      this.classList.add('d-none');
      if (btnCompleteJob) btnCompleteJob.classList.remove('d-none');
      if (currentStatusBadge) {
        currentStatusBadge.className = 'badge-soft-warning px-3 py-2';
        currentStatusBadge.innerHTML = '<i class="bi bi-tools me-1"></i> Sedang Dikerjakan';
      }
      const photoSection = document.getElementById('job-proof-section');
      if (photoSection) photoSection.classList.remove('d-none');
      BetulinAja.showToast('Pengerjaan Dimulai', 'Timer pengerjaan aktif. Jangan lupa mengambil foto Sebelum & Sesudah.', 'info');
    });
  }

  if (btnCompleteJob) {
    btnCompleteJob.addEventListener('click', function() {
      const modalEl = document.getElementById('jobCompletionModal');
      if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
      } else {
        BetulinAja.showToast('Pekerjaan Selesai!', 'Selamat, pekerjaan telah rampung! Penghasilan ditambahkan ke dompet Anda.', 'success');
        setTimeout(() => {
          window.location.href = '/partner/earnings';
        }, 1500);
      }
    });
  }

  // Before & After Photo Preview
  ['before', 'after'].forEach(type => {
    const input = document.getElementById(`upload-${type}-photo`);
    const previewContainer = document.getElementById(`preview-${type}-photo`);
    if (input && previewContainer) {
      input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(evt) {
            previewContainer.innerHTML = `
              <img src="${evt.target.result}" class="img-fluid rounded-3" style="max-height: 140px; width: 100%; object-fit: cover;">
              <small class="text-success d-block mt-1"><i class="bi bi-check-circle me-1"></i>Foto berhasil diunggah</small>
            `;
          };
          reader.readAsDataURL(file);
        }
      });
    }
  });
}
