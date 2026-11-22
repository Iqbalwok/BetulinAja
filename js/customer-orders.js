/**
 * BetulinAja — Customer Orders & Real-time Tracking
 * Manages order history listing, filtering, cancellation, and live tracking status sync.
 */

document.addEventListener('DOMContentLoaded', function() {
  const isHistory = document.getElementById('order-history-page');
  const isTracking = document.getElementById('tracking-page-container');

  if (isHistory || isTracking) {
    if (!Auth.requireAuth(['customer'])) return;
  }

  if (isHistory) {
    loadCustomerOrderHistory();
  }

  if (isTracking) {
    loadCustomerOrderTracking();
  }
});

/**
 * 1. Customer Order History
 */
async function loadCustomerOrderHistory(statusFilter = '') {
  const container = document.getElementById('order-history-list');
  if (!container) return;

  container.innerHTML = `
    <div class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="text-secondary small mt-2">Memuat riwayat pesanan Anda dari database...</p>
    </div>
  `;

  try {
    const params = {};
    if (statusFilter) params.status = statusFilter;

    const res = await ApiClient.get('/orders', params);
    const orders = res.data?.data || res.data || [];

    if (orders.length === 0) {
      container.innerHTML = `
        <div class="card card-custom p-5 text-center border-0 shadow-sm">
          <i class="bi bi-journal-x fs-1 text-secondary opacity-50 mb-2"></i>
          <h5 class="fw-bold text-dark">Belum Ada Pesanan</h5>
          <p class="text-secondary small mb-3">Anda belum memiliki riwayat pemesanan jasa service rumah.</p>
          <a href="booking.html" class="btn btn-primary-custom btn-sm px-4 mx-auto">
            <i class="bi bi-plus-circle me-1"></i> Pesan Jasa Sekarang
          </a>
        </div>
      `;
      return;
    }

    const statusMap = {
      pending: { label: 'Menunggu Konfirmasi', badge: 'bg-warning text-dark' },
      searching_technician: { label: 'Mencari Teknisi Terdekat', badge: 'bg-warning text-dark' },
      assigned: { label: 'Teknisi Ditugaskan', badge: 'bg-info text-dark' },
      accepted: { label: 'Pesanan Diterima Teknisi', badge: 'bg-info text-dark' },
      on_the_way: { label: 'Teknisi Dalam Perjalanan', badge: 'bg-primary' },
      arrived: { label: 'Teknisi Tiba di Lokasi', badge: 'bg-primary' },
      in_progress: { label: 'Sedang Dikerjakan', badge: 'bg-primary' },
      awaiting_customer_confirmation: { label: 'Pekerjaan Selesai (Menunggu Konfirmasi)', badge: 'bg-success' },
      completed: { label: 'Selesai & Bergaransi', badge: 'bg-success' },
      cancelled: { label: 'Dibatalkan', badge: 'bg-danger' }
    };

    container.innerHTML = orders.map(order => {
      const st = statusMap[order.status] || { label: order.status, badge: 'bg-secondary' };
      const canCancel = ['pending', 'searching_technician', 'assigned', 'accepted'].includes(order.status);

      return `
        <div class="card card-custom border-0 shadow-sm p-4 mb-3 hover-card">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 border-bottom pb-2 mb-3">
            <div>
              <span class="badge ${st.badge} small px-3 py-1.5 rounded-pill me-2">${st.label}</span>
              <strong class="text-dark small">#${order.order_number}</strong>
            </div>
            <span class="text-muted small">${ApiClient.formatDate(order.created_at)}</span>
          </div>

          <div class="row align-items-center">
            <div class="col-md-7">
              <h5 class="fw-bold text-dark mb-1">${order.service?.name || 'Layanan Perbaikan'}</h5>
              <p class="text-secondary small mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${order.address_text || '-'}</p>
              ${order.partner ? `
                <div class="d-flex align-items-center gap-2 mt-2">
                  <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 0.75rem;">
                    ${order.partner.name.substring(0, 2).toUpperCase()}
                  </div>
                  <span class="small text-dark">Teknisi: <strong>${order.partner.name}</strong></span>
                </div>
              ` : `
                <span class="small text-muted fst-italic"><i class="bi bi-hourglass-split me-1"></i>Sedang dicarikan mitra terbaik...</span>
              `}
            </div>

            <div class="col-md-5 text-md-end mt-3 mt-md-0">
              <span class="d-block text-muted small">Total Biaya</span>
              <h4 class="fw-bold text-primary mb-3">${ApiClient.formatRupiah(order.final_price || order.total_estimate)}</h4>
              
              <div class="d-flex gap-2 justify-content-md-end">
                ${canCancel ? `
                  <button class="btn btn-sm btn-outline-danger px-3" onclick="customerCancelOrder(${order.id})">
                    Batalkan
                  </button>
                ` : ''}
                ${order.status === 'completed' ? `
                  <button class="btn btn-sm btn-outline-success px-3 fw-semibold" onclick="printOrderInvoice(${order.id})">
                    <i class="bi bi-printer me-1"></i> Cetak Invoice
                  </button>
                ` : ''}
                <a href="tracking.html?id=${order.id}" class="btn btn-sm btn-primary-custom px-3 fw-semibold">
                  <i class="bi bi-geo-alt me-1"></i> Lacak Status
                </a>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

  } catch (err) {
    container.innerHTML = `<div class="alert alert-danger">Gagal memuat pesanan: ${err.message}</div>`;
  }
}

async function customerCancelOrder(orderId) {
  const reason = prompt('Masukkan alasan pembatalan pesanan:', 'Ada perubahan rencana jadwal');
  if (!reason) return;

  try {
    await ApiClient.post(`/orders/${orderId}/cancel`, { reason });
    ApiClient.showToast('Pesanan Dibatalkan', 'Pesanan Anda telah berhasil dibatalkan.', 'info');
    loadCustomerOrderHistory();
  } catch (err) {
    ApiClient.showToast('Gagal Membatalkan', err.message, 'danger');
  }
}

/**
 * 2. Live Order Tracking
 */
let trackingInterval = null;

async function loadCustomerOrderTracking() {
  const container = document.getElementById('tracking-page-container');
  if (!container) return;

  const urlParams = new URLSearchParams(window.location.search);
  let orderId = urlParams.get('id') || localStorage.getItem(CONFIG.STORAGE_KEYS.ACTIVE_ORDER);

  if (!orderId) {
    // Attempt to get customer's latest order
    try {
      const res = await ApiClient.get('/orders');
      const orders = res.data?.data || res.data || [];
      if (orders.length > 0) {
        orderId = orders[0].id;
      }
    } catch (e) {}
  }

  if (!orderId) {
    container.innerHTML = `
      <div class="card card-custom p-5 text-center border-0 shadow-sm my-5">
        <i class="bi bi-geo-alt fs-1 text-secondary opacity-50 mb-2"></i>
        <h5 class="fw-bold text-dark">Tidak Ada Pesanan yang Dilacak</h5>
        <p class="text-secondary small mb-3">Anda belum memiliki pesanan aktif untuk dilacak saat ini.</p>
        <a href="booking.html" class="btn btn-primary-custom btn-sm px-4 mx-auto">Buat Pesanan Baru</a>
      </div>
    `;
    return;
  }

  // Fetch and render initial status
  await fetchAndRenderTracking(orderId);

  // Poll status every 8 seconds for live progress
  if (trackingInterval) clearInterval(trackingInterval);
  trackingInterval = setInterval(() => {
    fetchAndRenderTracking(orderId, true);
  }, 8000);
}

async function fetchAndRenderTracking(orderId, isPolling = false) {
  const container = document.getElementById('tracking-page-container');
  if (!container) return;

  try {
    const res = await ApiClient.get(`/orders/${orderId}`);
    const order = res.data;

    renderTrackingDashboard(order);
  } catch (err) {
    if (!isPolling) {
      container.innerHTML = `<div class="alert alert-danger my-4">Gagal memuat detail pelacakan: ${err.message}</div>`;
    }
  }
}

function renderTrackingDashboard(order) {
  const steps = [
    { key: 'pending', title: 'Pesanan Dibuat', desc: 'Menunggu konfirmasi sistem' },
    { key: 'searching_technician', title: 'Pencarian Teknisi', desc: 'Menghubungi teknisi terdekat' },
    { key: 'assigned', title: 'Teknisi Ditugaskan', desc: 'Menunggu respon konfirmasi' },
    { key: 'accepted', title: 'Teknisi Siap', desc: 'Mempersiapkan peralatan' },
    { key: 'on_the_way', title: 'Dalam Perjalanan', desc: 'Menuju ke alamat Anda' },
    { key: 'arrived', title: 'Tiba di Lokasi', desc: 'Pemeriksaan awal kerusakan' },
    { key: 'in_progress', title: 'Proses Pengerjaan', desc: 'Service & perbaikan berjalan' },
    { key: 'completed', title: 'Selesai Bergaransi', desc: 'Perbaikan tuntas teruji' }
  ];

  const currentIdx = steps.findIndex(s => s.key === order.status);

  // Status message highlight
  let bannerText = 'Pesanan Anda sedang diproses oleh sistem.';
  let bannerColor = 'alert-info';

  if (order.status === 'on_the_way') {
    bannerText = `Teknisi ${order.partner?.name || ''} sedang dalam perjalanan menuju lokasi Anda!`;
    bannerColor = 'alert-primary';
  } else if (order.status === 'in_progress') {
    bannerText = 'Pengerjaan service sedang berlangsung dengan pengawasan standar SOP.';
    bannerColor = 'alert-warning';
  } else if (order.status === 'completed') {
    bannerText = 'Pekerjaan perbaikan telah selesai! Garansi resmi BetulinAja berlaku selama 30 hari.';
    bannerColor = 'alert-success';
  } else if (order.status === 'cancelled') {
    bannerText = `Pesanan ini telah dibatalkan. Alasan: ${order.cancellation_reason || 'Pembatalan pesanan'}`;
    bannerColor = 'alert-danger';
  }

  const container = document.getElementById('tracking-page-container');
  if (!container) return;

  container.innerHTML = `
    <!-- Top Status Banner -->
    <div class="alert ${bannerColor} border-0 shadow-sm p-3 rounded-4 mb-4 d-flex align-items-center gap-3">
      <i class="bi bi-bell-fill fs-4 flex-shrink-0"></i>
      <div class="flex-grow-1">
        <strong class="d-block text-dark small">${bannerText}</strong>
        <span class="text-secondary" style="font-size: 0.75rem;">Status otomatis diperbarui secara real-time. Terakhir cek: ${new Date().toLocaleTimeString('id-ID')} WIB</span>
      </div>
      <button class="btn btn-sm btn-outline-dark" onclick="fetchAndRenderTracking(${order.id})">
        <i class="bi bi-arrow-clockwise"></i>
      </button>
    </div>

    <div class="row g-4">
      <!-- Left Column: Stepper & Timeline -->
      <div class="col-lg-7">
        <div class="card card-custom p-4 border-0 shadow-sm mb-4">
          <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
            <div>
              <span class="badge bg-primary px-3 py-1.5 rounded-pill small">#${order.order_number}</span>
              <h5 class="fw-bold text-dark mt-2 mb-0">${order.service?.name || 'Perbaikan Rumah'}</h5>
            </div>
            <div class="text-end">
              <span class="d-block text-muted small">Total Estimasi</span>
              <strong class="text-primary fs-5">${ApiClient.formatRupiah(order.final_price || order.total_estimate)}</strong>
            </div>
          </div>

          <!-- Vertical Timeline Steps -->
          <div class="timeline-stepper">
            ${steps.map((st, i) => {
              const isPast = i <= currentIdx;
              const isCurrent = i === currentIdx;
              const isCancelled = order.status === 'cancelled';

              let dotClass = 'bg-light text-muted border';
              if (isCancelled && i === currentIdx) dotClass = 'bg-danger text-white';
              else if (isCurrent) dotClass = 'bg-primary text-white shadow ring-pulse';
              else if (isPast) dotClass = 'bg-success text-white';

              return `
                <div class="d-flex align-items-start gap-3 mb-3 position-relative">
                  <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 ${dotClass}" style="width: 32px; height: 32px; font-size: 0.8rem; z-index: 2;">
                    ${isPast ? '<i class="bi bi-check"></i>' : (i + 1)}
                  </div>
                  <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0 ${isCurrent ? 'text-primary' : (isPast ? 'text-dark' : 'text-muted')}">${st.title}</h6>
                    <span class="text-secondary small" style="font-size: 0.78rem;">${st.desc}</span>
                  </div>
                </div>
              `;
            }).join('')}
          </div>
        </div>

        <!-- Problem Detail & Customer Location -->
        <div class="card card-custom p-4 border-0 shadow-sm">
          <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle me-1 text-primary"></i> Rincian Pesanan & Lokasi</h6>
          <div class="mb-2">
            <span class="text-muted small d-block">Alamat Pengerjaan:</span>
            <p class="text-dark small mb-1 fw-semibold"><i class="bi bi-geo-alt-fill text-danger me-1"></i>${order.address_text || '-'}</p>
            <span class="text-secondary small fst-italic">${order.landmark || ''}</span>
          </div>
          <div class="border-top pt-2 mt-2">
            <span class="text-muted small d-block">Keluhan Pelanggan:</span>
            <p class="text-dark small mb-0">${order.problem_description || '-'}</p>
          </div>
        </div>
      </div>

      <!-- Right Column: Technician Card & Quick Contact -->
      <div class="col-lg-5">
        <div class="card card-custom p-4 border-0 shadow-sm mb-4">
          <h6 class="fw-bold text-dark mb-3">Teknisi Bertugas</h6>
          ${order.partner ? `
            <div class="d-flex align-items-center gap-3 mb-3 p-3 bg-light rounded-3">
              <div class="rounded-circle bg-primary text-white fw-bold d-flex align-items-center justify-content-center shadow-sm" style="width: 52px; height: 52px; font-size: 1.1rem;">
                ${order.partner.name.substring(0, 2).toUpperCase()}
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0">${order.partner.name}</h6>
                <span class="text-secondary small d-block">${order.partner.partner_profile?.specialty || 'Mitra Teknisi Resmi'}</span>
                <div class="d-flex align-items-center gap-1 text-warning small">
                  <i class="bi bi-star-fill"></i> ${order.partner.partner_profile?.average_rating || 4.9}
                  <span class="text-muted">(${order.partner.partner_profile?.completed_jobs || 32} order)</span>
                </div>
              </div>
            </div>

            <div class="d-flex flex-column gap-2 mb-3">
              <a href="https://wa.me/${(order.partner.phone || '').replace(/^0/, '62')}?text=${encodeURIComponent(`Halo ${order.partner.name}, saya pelanggan BetulinAja untuk pesanan #${order.order_number}`)}" target="_blank" class="btn btn-success w-100 py-2.5 fw-semibold shadow-sm">
                <i class="bi bi-whatsapp me-2"></i> Hubungi Teknisi via WhatsApp
              </a>
              <a href="tel:${order.partner.phone || ''}" class="btn btn-outline-custom w-100 py-2 small">
                <i class="bi bi-telephone-fill me-1"></i> Panggilan Telepon Seluler
              </a>
            </div>
          ` : `
            <div class="text-center py-4 bg-light rounded-3 mb-3">
              <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
              <h6 class="fw-bold text-dark small mb-1">Mencari Teknisi Terdekat...</h6>
              <p class="text-secondary" style="font-size: 0.75rem;">Sistem kami sedang mencocokkan pesanan Anda dengan mitra berkeahlian terbaik.</p>
            </div>
          `}

          <!-- Safety Guidelines -->
          <div class="p-3 bg-soft-primary rounded-3 border border-primary-subtle mb-3">
            <span class="fw-bold text-primary small d-block mb-1"><i class="bi bi-shield-check me-1"></i> Jaminan Keamanan BetulinAja</span>
            <ul class="text-secondary small mb-0 ps-3" style="font-size: 0.78rem;">
              <li>Teknisi telah melewati verifikasi KTP & SKCK.</li>
              <li>Bayar hanya setelah pekerjaan selesai diuji.</li>
              <li>Garansi service 30 hari untuk kerusakan yang sama.</li>
            </ul>
          </div>

          <!-- Official Invoice Download (When Completed) -->
          ${order.status === 'completed' ? `
            <button class="btn btn-outline-success w-100 py-2.5 fw-semibold shadow-sm mb-3" onclick="printOrderInvoice(${order.id})">
              <i class="bi bi-printer me-2"></i> Cetak / Simpan Invoice Resmi
            </button>
          ` : ''}
        </div>

        <!-- Order Cancellation Option -->
        ${['pending', 'searching_technician', 'assigned', 'accepted'].includes(order.status) ? `
          <button class="btn btn-outline-danger w-100 py-2 small" onclick="customerCancelOrder(${order.id})">
            <i class="bi bi-x-circle me-1"></i> Batalkan Pesanan Ini
          </button>
        ` : ''}
      </div>
    </div>
  `;
}

window.loadCustomerOrderHistory = loadCustomerOrderHistory;
window.customerCancelOrder = customerCancelOrder;
window.fetchAndRenderTracking = fetchAndRenderTracking;
