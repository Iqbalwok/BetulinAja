/**
 * BetulinAja — Partner (Technician) Portal Script
 * Handles real-time metrics, order acceptance, lifecycle status updates,
 * and profile/availability synchronization with Laravel Backend REST API.
 */

document.addEventListener('DOMContentLoaded', function() {
  // Page identification
  const isDashboard = document.getElementById('partner-dashboard-page');
  const isOrders = document.getElementById('partner-orders-page');
  const isActiveJob = document.getElementById('partner-active-job-page');

  if (isDashboard || isOrders || isActiveJob) {
    if (!Auth.requireAuth(['partner'])) return;
    updatePartnerSidebarProfile();
  }

  if (isDashboard) {
    loadPartnerDashboard();
  }

  if (isOrders) {
    loadPartnerOrders();
  }

  if (isActiveJob) {
    loadPartnerActiveJob();
  }
});

function updatePartnerSidebarProfile() {
  const user = Auth.getUser();
  if (!user) return;

  const names = document.querySelectorAll('.partner-name-display');
  names.forEach(el => el.textContent = user.name);

  const roles = document.querySelectorAll('.partner-specialty-display');
  roles.forEach(el => {
    const prof = user.partner_profile;
    if (prof?.skills?.length) {
      el.textContent = prof.skills.map(s => s.category?.name || 'Teknisi').join(', ');
    } else {
      el.textContent = 'Mitra Teknisi Terverifikasi';
    }
  });

  const ratingDisplays = document.querySelectorAll('.partner-rating-display');
  ratingDisplays.forEach(el => {
    const rating = user.partner_profile?.average_rating || 4.9;
    const count = user.partner_profile?.total_reviews || 28;
    el.innerHTML = `<i class="bi bi-star-fill text-warning"></i> ${rating} <span class="text-muted">(${count})</span>`;
  });
}

/**
 * 1. Partner Dashboard
 */
async function loadPartnerDashboard() {
  try {
    const res = await ApiClient.get('/partner/dashboard');
    if (res.success && res.data) {
      const data = res.data;
      const stats = data.stats || {};

      // Balance & Summary stats
      const balEl = document.getElementById('partner-balance-stat');
      if (balEl) balEl.textContent = ApiClient.formatRupiah(data.partner_profile?.balance || stats.total_earnings || 0);

      const todayJobsEl = document.getElementById('partner-today-jobs-stat');
      if (todayJobsEl) todayJobsEl.textContent = stats.today_jobs_count || 0;

      const completedEl = document.getElementById('partner-completed-jobs-stat');
      if (completedEl) completedEl.textContent = stats.completed_jobs_count || data.partner_profile?.completed_jobs || 0;

      const ratingEl = document.getElementById('partner-avg-rating-stat');
      if (ratingEl) ratingEl.textContent = data.partner_profile?.average_rating || '4.9';

      // Availability status toggle
      const statusSelect = document.getElementById('partner-availability-toggle');
      if (statusSelect && data.partner_profile) {
        statusSelect.value = data.partner_profile.availability_status || 'online';
        statusSelect.addEventListener('change', async function() {
          try {
            await ApiClient.post('/partner/status', { 
              availability_status: this.value,
              status: this.value 
            });
            ApiClient.showToast('Status Diperbarui', `Status Anda sekarang: ${this.value.toUpperCase()}`, 'success');
          } catch (e) {
            ApiClient.showToast('Gagal Ubah Status', e.message, 'danger');
          }
        });
      }

      // Render Active Job card
      renderDashboardActiveJob(data.active_order);

      // Render Incoming Assigned Orders
      renderDashboardIncomingOrders(data.assigned_orders || []);
    }
  } catch (err) {
    console.error('Failed loading partner dashboard:', err);
    ApiClient.showToast('Error', 'Gagal memuat data dashboard: ' + err.message, 'danger');
  }
}

function renderDashboardActiveJob(order) {
  const container = document.getElementById('active-job-container');
  if (!container) return;

  if (!order) {
    container.innerHTML = `
      <div class="card card-custom p-4 border-0 shadow-sm text-center">
        <div class="text-muted py-3">
          <i class="bi bi-clipboard-check fs-1 text-secondary opacity-50 d-block mb-2"></i>
          <h6 class="fw-bold text-dark">Tidak Ada Pekerjaan yang Sedang Berjalan</h6>
          <p class="small text-secondary mb-0">Pesanan yang Anda terima dan sedang dikerjakan akan tampil di sini.</p>
        </div>
      </div>
    `;
    return;
  }

  const statusMap = {
    accepted: { label: 'Diterima - Menunggu Keberangkatan', badge: 'bg-primary' },
    on_the_way: { label: 'Dalam Perjalanan ke Lokasi', badge: 'bg-info text-dark' },
    arrived: { label: 'Tiba di Lokasi Pelanggan', badge: 'bg-warning text-dark' },
    in_progress: { label: 'Sedang Dikerjakan', badge: 'bg-success' }
  };

  const st = statusMap[order.status] || { label: order.status, badge: 'bg-secondary' };

  container.innerHTML = `
    <div class="card card-custom border-primary border-2 shadow-sm p-4">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <span class="badge ${st.badge} px-3 py-1.5 rounded-pill small">${st.label}</span>
        <span class="text-muted small">#${order.order_number}</span>
      </div>
      <h5 class="fw-bold text-dark mb-1">${order.service?.name || 'Layanan Perbaikan'}</h5>
      <p class="text-secondary small mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${order.address_text || '-'}</p>
      
      <div class="d-flex align-items-center justify-content-between p-3 bg-light rounded-3 mb-3">
        <div>
          <span class="d-block text-muted small">Pelanggan</span>
          <strong class="text-dark">${order.customer?.name || 'Pelanggan'}</strong>
        </div>
        <div>
          <span class="d-block text-muted small text-end">Pendapatan Bersih</span>
          <strong class="text-success">${ApiClient.formatRupiah(order.partner_earning || order.final_price || order.total_estimate)}</strong>
        </div>
      </div>

      <div class="d-flex gap-2">
        <a href="active-job.html?id=${order.id}" class="btn btn-primary-custom flex-grow-1 py-2 fw-semibold">
          <i class="bi bi-arrow-right-circle me-1"></i> Buka Manajemen Pekerjaan Ini
        </a>
        <a href="https://wa.me/${(order.customer?.phone || '').replace(/^0/, '62')}" target="_blank" class="btn btn-success px-3">
          <i class="bi bi-whatsapp"></i>
        </a>
      </div>
    </div>
  `;
}

function renderDashboardIncomingOrders(orders) {
  const container = document.getElementById('incoming-orders-container');
  if (!container) return;

  if (orders.length === 0) {
    container.innerHTML = `
      <div class="text-center py-4 text-muted">
        <i class="bi bi-inbox fs-2 opacity-50 d-block mb-1"></i>
        <span class="small">Belum ada pesanan baru yang ditugaskan kepada Anda saat ini.</span>
      </div>
    `;
    return;
  }

  container.innerHTML = orders.map(order => `
    <div class="card card-custom border-0 shadow-sm p-3 mb-3" id="order-card-${order.id}">
      <div class="d-flex align-items-center justify-content-between mb-2">
        <span class="badge bg-danger-subtle text-danger px-2.5 py-1 rounded-pill small fw-semibold">Tugas Masuk</span>
        <span class="text-muted small">${ApiClient.formatDate(order.created_at)}</span>
      </div>
      <h6 class="fw-bold text-dark mb-1">${order.service?.name || 'Perbaikan'}</h6>
      <p class="text-secondary small mb-2"><i class="bi bi-geo-alt me-1 text-primary"></i>${order.address_text || 'Alamat'}</p>
      <div class="d-flex align-items-center justify-content-between border-top pt-2 mt-2">
        <span class="fw-bold text-success">${ApiClient.formatRupiah(order.partner_earning || order.total_estimate)}</span>
        <div class="d-flex gap-1.5">
          <button class="btn btn-sm btn-outline-danger px-2.5" onclick="partnerRejectOrder(${order.id})">Tolak</button>
          <button class="btn btn-sm btn-primary-custom px-3" onclick="partnerAcceptOrder(${order.id})">Terima Pekerjaan</button>
        </div>
      </div>
    </div>
  `).join('');
}

async function partnerAcceptOrder(orderId) {
  try {
    const res = await ApiClient.post(`/partner/orders/${orderId}/accept`);
    ApiClient.showToast('Pekerjaan Diterima', 'Anda telah menerima pesanan ini. Mengarahkan ke pengerjaan...', 'success');
    setTimeout(() => {
      window.location.href = `active-job.html?id=${orderId}`;
    }, 1200);
  } catch (err) {
    ApiClient.showToast('Gagal Menerima', err.message, 'danger');
  }
}

async function partnerRejectOrder(orderId) {
  if (!confirm('Apakah Anda yakin ingin menolak pesanan ini? Pesanan akan dialihkan ke teknisi lain.')) return;
  try {
    await ApiClient.post(`/partner/orders/${orderId}/reject`, { reason: 'Mitra sedang berhalangan' });
    ApiClient.showToast('Pesanan Ditolak', 'Pesanan telah dialihkan.', 'info');
    const card = document.getElementById(`order-card-${orderId}`);
    if (card) card.remove();
  } catch (err) {
    ApiClient.showToast('Gagal Menolak', err.message, 'danger');
  }
}

/**
 * 2. Partner Orders Page (Full List)
 */
async function loadPartnerOrders() {
  const container = document.getElementById('partner-orders-list');
  if (!container) return;

  container.innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-secondary small mt-2">Memuat daftar pesanan Anda...</p>
    </div>
  `;

  try {
    const res = await ApiClient.get('/partner/orders');
    const orders = res.data?.data || res.data || [];

    if (orders.length === 0) {
      container.innerHTML = `
        <div class="card card-custom p-5 text-center border-0 shadow-sm">
          <i class="bi bi-inbox fs-1 text-secondary opacity-50 mb-2"></i>
          <h6 class="fw-bold text-dark">Belum Ada Riwayat Pesanan</h6>
          <p class="text-secondary small mb-0">Pesanan yang ditugaskan kepada Anda akan tampil pada halaman ini.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = orders.map(order => {
      let badgeClass = 'bg-secondary';
      let statusLabel = order.status;

      if (order.status === 'completed') { badgeClass = 'bg-success'; statusLabel = 'Selesai'; }
      else if (order.status === 'in_progress') { badgeClass = 'bg-primary'; statusLabel = 'Sedang Dikerjakan'; }
      else if (order.status === 'assigned') { badgeClass = 'bg-warning text-dark'; statusLabel = 'Menunggu Konfirmasi'; }
      else if (order.status === 'accepted') { badgeClass = 'bg-info text-dark'; statusLabel = 'Diterima'; }
      else if (order.status === 'on_the_way') { badgeClass = 'bg-info text-dark'; statusLabel = 'Dalam Perjalanan'; }
      else if (order.status === 'arrived') { badgeClass = 'bg-warning text-dark'; statusLabel = 'Tiba di Lokasi'; }
      else if (order.status === 'cancelled') { badgeClass = 'bg-danger'; statusLabel = 'Dibatalkan'; }

      return `
        <div class="card card-custom border-0 shadow-sm p-4 mb-3">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-2 mb-3">
            <div>
              <span class="badge ${badgeClass} small px-3 py-1.5 rounded-pill me-2">${statusLabel}</span>
              <strong class="text-dark small">#${order.order_number}</strong>
            </div>
            <span class="text-muted small">${ApiClient.formatDate(order.created_at)}</span>
          </div>

          <div class="row align-items-center">
            <div class="col-md-7">
              <h5 class="fw-bold text-dark mb-1">${order.service?.name || 'Layanan Perbaikan'}</h5>
              <p class="text-secondary small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${order.address_text || '-'}</p>
              <p class="text-muted small mb-0"><i class="bi bi-person-fill me-1"></i>Pelanggan: <strong>${order.customer?.name || '-'}</strong> (${order.customer?.phone || '-'})</p>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
              <span class="d-block text-muted small">Pendapatan Bersih</span>
              <h4 class="fw-bold text-success mb-2">${ApiClient.formatRupiah(order.partner_earning || order.final_price || order.total_estimate)}</h4>
              ${order.status === 'assigned' ? `
                <div class="d-flex gap-1.5 justify-content-md-end">
                  <button class="btn btn-sm btn-outline-danger px-3" onclick="partnerRejectOrder(${order.id})">Tolak</button>
                  <button class="btn btn-sm btn-primary-custom px-3 fw-semibold" onclick="partnerAcceptOrder(${order.id})">Terima</button>
                </div>
              ` : `
                <a href="active-job.html?id=${order.id}" class="btn btn-sm btn-outline-custom px-3 fw-semibold">
                  <i class="bi bi-eye me-1"></i> Lihat Detail
                </a>
              `}
            </div>
          </div>
        </div>
      `;
    }).join('');

  } catch (err) {
    container.innerHTML = `
      <div class="alert alert-danger">Gagal memuat pesanan: ${err.message}</div>
    `;
  }
}

/**
 * 3. Partner Active Job Execution
 */
async function loadPartnerActiveJob() {
  const container = document.getElementById('active-job-detail-card');
  if (!container) return;

  const urlParams = new URLSearchParams(window.location.search);
  let orderId = urlParams.get('id');

  try {
    let order = null;
    if (orderId) {
      const res = await ApiClient.get(`/partner/orders/${orderId}`);
      order = res.data;
    } else {
      // Find first active job
      const res = await ApiClient.get('/partner/dashboard');
      order = res.data?.active_order;
    }

    if (!order) {
      container.innerHTML = `
        <div class="card card-custom p-5 text-center border-0 shadow-sm">
          <i class="bi bi-check2-circle fs-1 text-success mb-2"></i>
          <h5 class="fw-bold text-dark">Tidak Ada Pekerjaan Aktif</h5>
          <p class="text-secondary small mb-3">Silakan periksa halaman Pesanan Masuk untuk menerima pekerjaan baru.</p>
          <a href="orders.html" class="btn btn-primary-custom btn-sm px-4 mx-auto">Lihat Daftar Pesanan</a>
        </div>
      `;
      return;
    }

    renderActiveJobWorkspace(order);

  } catch (err) {
    container.innerHTML = `
      <div class="alert alert-danger">Gagal memuat pekerjaan: ${err.message}</div>
    `;
  }
}

function renderActiveJobWorkspace(order) {
  const container = document.getElementById('active-job-detail-card');
  if (!container) return;

  const steps = [
    { key: 'accepted', title: 'Pesanan Diterima', icon: 'bi-check2' },
    { key: 'on_the_way', title: 'Menuju Lokasi', icon: 'bi-truck' },
    { key: 'arrived', title: 'Tiba di Lokasi', icon: 'bi-geo-alt' },
    { key: 'in_progress', title: 'Pengerjaan Service', icon: 'bi-tools' },
    { key: 'completed', title: 'Selesai', icon: 'bi-patch-check' }
  ];

  const currentIdx = steps.findIndex(s => s.key === order.status);

  let nextActionBtn = '';
  if (order.status === 'accepted') {
    nextActionBtn = `<button class="btn btn-primary-custom btn-lg w-100 fw-bold py-3 shadow" onclick="updateJobStatus(${order.id}, 'on_the_way')"><i class="bi bi-truck me-2"></i>Berangkat Menuju Lokasi</button>`;
  } else if (order.status === 'on_the_way') {
    nextActionBtn = `<button class="btn btn-warning btn-lg w-100 fw-bold py-3 shadow text-dark" onclick="updateJobStatus(${order.id}, 'arrived')"><i class="bi bi-geo-alt-fill me-2"></i>Saya Sudah Tiba di Lokasi</button>`;
  } else if (order.status === 'arrived') {
    nextActionBtn = `<button class="btn btn-primary-custom btn-lg w-100 fw-bold py-3 shadow" onclick="updateJobStatus(${order.id}, 'in_progress')"><i class="bi bi-play-circle-fill me-2"></i>Mulai Kerjakan Perbaikan</button>`;
  } else if (order.status === 'in_progress') {
    nextActionBtn = `<button class="btn btn-success btn-lg w-100 fw-bold py-3 shadow" onclick="completeJobModal(${order.id})"><i class="bi bi-check2-circle me-2"></i>Selesaikan Pekerjaan</button>`;
  } else if (order.status === 'completed') {
    nextActionBtn = `<div class="alert alert-success text-center fw-bold mb-0 py-3"><i class="bi bi-check-circle-fill me-2"></i>Pekerjaan Ini Telah Selesai Ditangani</div>`;
  }

  container.innerHTML = `
    <!-- Top Card Details -->
    <div class="card card-custom border-0 shadow-sm p-4 mb-4">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-3 mb-3">
        <div>
          <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill small fw-semibold">Pekerjaan Aktif</span>
          <h4 class="fw-bold text-dark mt-1 mb-0">${order.service?.name || 'Layanan Perbaikan'}</h4>
        </div>
        <div class="text-md-end">
          <span class="d-block text-muted small">No. Pesanan:</span>
          <strong class="fs-6 text-dark">#${order.order_number}</strong>
        </div>
      </div>

      <!-- Stepper visual -->
      <div class="stepper-horizontal mb-4 py-2">
        <div class="d-flex justify-content-between position-relative">
          ${steps.map((st, i) => {
            const isDone = i <= currentIdx;
            const isCurrent = i === currentIdx;
            return `
              <div class="text-center flex-fill position-relative">
                <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center shadow-sm ${isCurrent ? 'bg-primary text-white border border-4 border-primary-subtle' : (isDone ? 'bg-success text-white' : 'bg-light text-muted border')}" style="width: 36px; height: 36px;">
                  <i class="bi ${st.icon}"></i>
                </div>
                <span class="d-block small mt-1 ${isCurrent ? 'fw-bold text-primary' : (isDone ? 'fw-semibold text-dark' : 'text-muted')}" style="font-size: 0.75rem;">${st.title}</span>
              </div>
            `;
          }).join('')}
        </div>
      </div>

      <!-- Action Button for Next Transition -->
      <div class="mb-4">
        ${nextActionBtn}
      </div>

      <!-- Customer & Location Information -->
      <div class="row g-3">
        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3 h-100 border">
            <span class="text-muted small fw-semibold text-uppercase d-block mb-2">Informasi Pelanggan</span>
            <h6 class="fw-bold text-dark mb-1">${order.customer?.name || 'Pelanggan'}</h6>
            <p class="text-secondary small mb-2"><i class="bi bi-telephone me-1 text-primary"></i>${order.customer?.phone || '-'}</p>
            <div class="d-flex gap-2">
              <a href="tel:${order.customer?.phone || ''}" class="btn btn-outline-custom btn-sm flex-fill">
                <i class="bi bi-telephone-fill me-1"></i> Telepon
              </a>
              <a href="https://wa.me/${(order.customer?.phone || '').replace(/^0/, '62')}" target="_blank" class="btn btn-success btn-sm flex-fill">
                <i class="bi bi-whatsapp me-1"></i> Chat WhatsApp
              </a>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="p-3 bg-light rounded-3 h-100 border">
            <span class="text-muted small fw-semibold text-uppercase d-block mb-2">Lokasi Perbaikan</span>
            <p class="text-dark small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${order.address_text || '-'}</p>
            <span class="d-block text-muted small">Patokan / Catatan: ${order.landmark || 'Tidak ada catatan tambahan'}</span>
          </div>
        </div>
      </div>

      <!-- Problem & Damage Photos -->
      <div class="mt-3 p-3 bg-light rounded-3 border">
        <span class="text-muted small fw-semibold text-uppercase d-block mb-1">Deskripsi Kerusakan</span>
        <p class="text-dark small mb-2">${order.problem_description || 'Tidak ada rincian kendala.'}</p>
        ${order.photos?.length ? `
          <span class="text-muted small d-block mb-1">Foto Kerusakan dari Pelanggan:</span>
          <div class="d-flex flex-wrap gap-2">
            ${order.photos.map(p => `<img src="${p.file_path.startsWith('http') ? p.file_path : '/storage/' + p.file_path}" alt="Foto" class="rounded border" style="width: 70px; height: 70px; object-fit: cover;">`).join('')}
          </div>
        ` : ''}
      </div>
    </div>
  `;
}

async function updateJobStatus(orderId, nextStatus) {
  try {
    await ApiClient.patch(`/partner/orders/${orderId}/status`, { status: nextStatus });
    ApiClient.showToast('Status Diperbarui', `Status pekerjaan berhasil diubah ke ${nextStatus}.`, 'success');
    loadPartnerActiveJob();
  } catch (err) {
    ApiClient.showToast('Gagal Ubah Status', err.message, 'danger');
  }
}

function completeJobModal(orderId) {
  const finalPrice = prompt('Masukkan nominal total biaya yang disepakati (kosongkan jika sesuai estimasi):', '');
  const body = { status: 'completed' };
  if (finalPrice && !isNaN(finalPrice)) {
    body.final_price = parseFloat(finalPrice);
  }

  updateJobStatusWithPayload(orderId, body);
}

async function updateJobStatusWithPayload(orderId, payload) {
  try {
    await ApiClient.patch(`/partner/orders/${orderId}/status`, payload);
    ApiClient.showToast('Pekerjaan Selesai!', 'Selamat! Pekerjaan telah berhasil ditutup dan saldo ditambahkan.', 'success', 3000);
    setTimeout(() => {
      window.location.href = 'dashboard.html';
    }, 1500);
  } catch (err) {
    ApiClient.showToast('Gagal Selesaikan Pekerjaan', err.message, 'danger');
  }
}

window.partnerAcceptOrder = partnerAcceptOrder;
window.partnerRejectOrder = partnerRejectOrder;
window.updateJobStatus = updateJobStatus;
window.completeJobModal = completeJobModal;
