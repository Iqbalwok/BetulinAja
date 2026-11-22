/**
 * BetulinAja — Admin Portal Script
 * Handles platform analytics, all-orders management, partner force-assignment,
 * service catalog moderation, and system settings.
 */

document.addEventListener('DOMContentLoaded', function() {
  const isDashboard = document.getElementById('admin-dashboard-page');
  const isOrders = document.getElementById('admin-orders-page');

  if (isDashboard || isOrders) {
    if (!Auth.requireAuth(['admin'])) return;
  }

  if (isDashboard) {
    loadAdminDashboard();
  }

  if (isOrders) {
    loadAdminOrders();
  }
});

/**
 * 1. Admin Dashboard
 */
async function loadAdminDashboard() {
  try {
    const res = await ApiClient.get('/admin/dashboard');
    if (res.success && res.data) {
      const data = res.data;
      const stats = data.stats || {};

      // Fill Metric Cards
      const revEl = document.getElementById('admin-total-revenue-stat');
      if (revEl) revEl.textContent = ApiClient.formatRupiah(stats.total_revenue || 0);

      const commEl = document.getElementById('admin-total-commission-stat');
      if (commEl) commEl.textContent = ApiClient.formatRupiah(stats.total_commission || (stats.total_revenue * 0.15) || 0);

      const totalOrdersEl = document.getElementById('admin-total-orders-stat');
      if (totalOrdersEl) totalOrdersEl.textContent = stats.total_orders || 0;

      const activeOrdersEl = document.getElementById('admin-active-orders-stat');
      if (activeOrdersEl) activeOrdersEl.textContent = stats.active_orders || 0;

      const partnerCountEl = document.getElementById('admin-partners-count-stat');
      if (partnerCountEl) partnerCountEl.textContent = stats.total_partners || 0;

      const customerCountEl = document.getElementById('admin-customers-count-stat');
      if (customerCountEl) customerCountEl.textContent = stats.total_customers || 0;

      // Recent orders list
      renderAdminRecentOrders(data.recent_orders || []);
    }
  } catch (err) {
    console.error('Failed loading admin dashboard:', err);
    ApiClient.showToast('Gagal Memuat Data', err.message, 'danger');
  }
}

function renderAdminRecentOrders(orders) {
  const tbody = document.getElementById('admin-recent-orders-table-body');
  if (!tbody) return;

  if (orders.length === 0) {
    tbody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-muted small">Belum ada pesanan terbaru.</td></tr>`;
    return;
  }

  tbody.innerHTML = orders.map(order => {
    let badgeClass = 'bg-secondary';
    if (order.status === 'completed') badgeClass = 'bg-success';
    else if (order.status === 'in_progress') badgeClass = 'bg-primary';
    else if (order.status === 'searching_technician' || order.status === 'pending') badgeClass = 'bg-warning text-dark';
    else if (order.status === 'cancelled') badgeClass = 'bg-danger';

    return `
      <tr>
        <td><strong class="text-dark small">#${order.order_number}</strong></td>
        <td>
          <span class="d-block fw-semibold text-dark small">${order.customer?.name || '-'}</span>
          <span class="text-muted" style="font-size: 0.72rem;">${order.customer?.phone || ''}</span>
        </td>
        <td><span class="small text-secondary">${order.service?.name || '-'}</span></td>
        <td>
          <span class="small ${order.partner ? 'text-primary fw-semibold' : 'text-muted fst-italic'}">
            ${order.partner?.name || 'Belum Ditugaskan'}
          </span>
        </td>
        <td><span class="badge ${badgeClass} small rounded-pill px-2.5 py-1">${order.status}</span></td>
        <td class="text-end">
          <a href="orders.html?search=${order.order_number}" class="btn btn-sm btn-outline-custom py-1 px-2.5">
            <i class="bi bi-eye"></i>
          </a>
        </td>
      </tr>
    `;
  }).join('');
}

/**
 * 2. Admin All Orders Management
 */
let allTechniciansCache = [];

async function loadAdminOrders(statusFilter = '') {
  const tbody = document.getElementById('admin-orders-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="ms-2 small text-secondary">Memuat seluruh pesanan...</span></td></tr>`;

  try {
    const params = {};
    if (statusFilter) params.status = statusFilter;

    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) params.search = searchParam;

    const res = await ApiClient.get('/admin/orders', params);
    const orders = res.data?.data || res.data || [];

    // Preload technicians for assignment modal
    if (allTechniciansCache.length === 0) {
      try {
        const techRes = await ApiClient.get('/technicians');
        allTechniciansCache = techRes.data || [];
      } catch (e) {}
    }

    if (orders.length === 0) {
      tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5 text-muted small"><i class="bi bi-inbox fs-3 d-block mb-1 opacity-50"></i>Tidak ada pesanan ditemukan.</td></tr>`;
      return;
    }

    tbody.innerHTML = orders.map(order => {
      let badgeClass = 'bg-secondary';
      if (order.status === 'completed') badgeClass = 'bg-success';
      else if (order.status === 'in_progress') badgeClass = 'bg-primary';
      else if (order.status === 'searching_technician' || order.status === 'pending') badgeClass = 'bg-warning text-dark';
      else if (order.status === 'assigned') badgeClass = 'bg-info text-dark';
      else if (order.status === 'cancelled') badgeClass = 'bg-danger';

      return `
        <tr>
          <td><strong class="text-dark small">#${order.order_number}</strong></td>
          <td>
            <span class="d-block fw-semibold text-dark small">${order.customer?.name || '-'}</span>
            <span class="text-muted" style="font-size: 0.72rem;">${order.customer?.phone || ''}</span>
          </td>
          <td><span class="small text-secondary">${order.service?.name || '-'}</span></td>
          <td>
            <span class="small ${order.partner ? 'text-primary fw-semibold' : 'text-muted fst-italic'}">
              ${order.partner?.name || 'Belum Ditugaskan'}
            </span>
          </td>
          <td><strong class="text-success small">${ApiClient.formatRupiah(order.final_price || order.total_estimate)}</strong></td>
          <td><span class="badge ${badgeClass} small rounded-pill px-2.5 py-1">${order.status}</span></td>
          <td class="text-end">
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-custom dropdown-toggle py-1 px-2.5" type="button" data-bs-toggle="dropdown">
                Aksi
              </button>
              <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 small">
                <li><button class="dropdown-item py-1.5" onclick="openAssignModal(${order.id}, '${order.order_number}')"><i class="bi bi-person-plus text-primary me-2"></i>Tugaskan Teknisi</button></li>
                <li><button class="dropdown-item py-1.5 text-danger" onclick="adminCancelOrder(${order.id})"><i class="bi bi-x-circle me-2"></i>Batalkan Pesanan</button></li>
              </ul>
            </div>
          </td>
        </tr>
      `;
    }).join('');

  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-danger small">${err.message}</td></tr>`;
  }
}

function openAssignModal(orderId, orderNumber) {
  const select = document.getElementById('assign-technician-select');
  const orderNumDisplay = document.getElementById('assign-order-number-display');
  const hiddenInput = document.getElementById('assign-order-id-input');

  if (orderNumDisplay) orderNumDisplay.textContent = `#${orderNumber}`;
  if (hiddenInput) hiddenInput.value = orderId;

  if (select) {
    select.innerHTML = '<option value="">-- Pilih Mitra Teknisi --</option>' +
      allTechniciansCache.map(t => `<option value="${t.id}">${t.name} — ${t.specialty || 'Teknisi'} (Rating: ${t.average_rating || 4.9})</option>`).join('');
  }

  const modalEl = document.getElementById('assignTechnicianModal');
  if (modalEl) {
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  } else {
    // Fallback prompt
    const techId = prompt(`Tugaskan Teknisi untuk pesanan #${orderNumber}. Masukkan ID User Teknisi:`, '2');
    if (techId) submitAssignPartner(orderId, techId);
  }
}

async function submitAssignPartner(orderId, partnerId) {
  try {
    await ApiClient.post(`/admin/orders/${orderId}/assign`, { partner_id: partnerId });
    ApiClient.showToast('Penugasan Berhasil', 'Mitra teknisi berhasil ditugaskan ke pesanan ini.', 'success');
    
    // Close modal if exists
    const modalEl = document.getElementById('assignTechnicianModal');
    if (modalEl) {
      const modal = bootstrap.Modal.getInstance(modalEl);
      if (modal) modal.hide();
    }

    loadAdminOrders();
  } catch (err) {
    ApiClient.showToast('Gagal Menugaskan', err.message, 'danger');
  }
}

async function adminCancelOrder(orderId) {
  const reason = prompt('Masukkan alasan pembatalan pesanan oleh admin:', 'Dibatalkan atas permintaan sistem / kendala operasional');
  if (!reason) return;

  try {
    await ApiClient.post(`/admin/orders/${orderId}/cancel`, { reason });
    ApiClient.showToast('Pesanan Dibatalkan', 'Pesanan telah dibatalkan.', 'info');
    loadAdminOrders();
  } catch (err) {
    ApiClient.showToast('Gagal Membatalkan', err.message, 'danger');
  }
}

window.loadAdminOrders = loadAdminOrders;
window.openAssignModal = openAssignModal;
window.submitAssignPartner = submitAssignPartner;
window.adminCancelOrder = adminCancelOrder;
