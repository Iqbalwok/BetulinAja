/**
 * BetulinAja — Admin Portal Script
 * Handles platform analytics, all-orders management, partner force-assignment,
 * partner verification approvals, user moderation (activate/suspend), and service catalog.
 */

document.addEventListener('DOMContentLoaded', function() {
  const isDashboard = document.getElementById('admin-dashboard-page');
  const isOrders = document.getElementById('admin-orders-page');

  if (isDashboard || isOrders) {
    if (!Auth.requireAuth(['admin'])) return;
  }

  if (isDashboard) {
    loadAdminDashboard();
    loadPendingVerifications();
    loadAdminUsers();
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
          <a href="admin-orders.html?search=${order.order_number}" class="btn btn-sm btn-outline-custom py-1 px-2.5">
            <i class="bi bi-eye"></i>
          </a>
        </td>
      </tr>
    `;
  }).join('');
}

/**
 * 2. Partner Verification Approvals
 */
async function loadPendingVerifications() {
  const container = document.getElementById('admin-pending-partners-container');
  const badge = document.getElementById('pending-partners-badge');
  if (!container) return;

  container.innerHTML = `
    <div class="text-center py-3">
      <div class="spinner-border spinner-border-sm text-primary"></div>
      <span class="ms-2 small text-secondary">Memuat antrean verifikasi...</span>
    </div>
  `;

  try {
    const res = await ApiClient.get('/admin/partners/verification', { status: 'pending' });
    const rawData = res.data?.data || res.data || [];
    const partners = Array.isArray(rawData) ? rawData : [];

    if (badge) badge.textContent = `${partners.length} Menunggu`;

    if (partners.length === 0) {
      container.innerHTML = `
        <div class="text-center py-4 text-muted">
          <i class="bi bi-patch-check fs-2 text-success opacity-75 d-block mb-1"></i>
          <span class="small fw-semibold text-dark">Tidak Ada Antrean Verifikasi</span>
          <p class="text-secondary small mb-0" style="font-size: 0.75rem;">Semua calon mitra teknisi telah ditinjau.</p>
        </div>
      `;
      return;
    }

    container.innerHTML = partners.map(p => {
      const skills = (p.partner_profile?.skills || []).map(s => s.category?.name).filter(Boolean).join(', ') || 'Teknisi Umum';
      return `
        <div class="border rounded-3 p-3 mb-3 bg-light" id="partner-verify-card-${p.id}">
          <div class="d-flex align-items-center justify-content-between mb-2">
            <div class="d-flex align-items-center gap-2">
              <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                ${(p.name || 'T').substring(0, 2).toUpperCase()}
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0 small">${p.name}</h6>
                <span class="text-muted" style="font-size: 0.72rem;">${p.phone || '-'}</span>
              </div>
            </div>
            <span class="badge bg-warning text-dark small py-1 px-2" style="font-size: 0.68rem;">Pending</span>
          </div>
          <p class="text-secondary small mb-2" style="font-size: 0.78rem;">
            <strong>Keahlian:</strong> ${skills}<br>
            <span class="text-muted">${p.partner_profile?.description || 'Pengajuan verifikasi mitra baru.'}</span>
          </p>
          <div class="d-flex gap-2 pt-2 border-top">
            <button class="btn btn-sm btn-outline-danger flex-fill py-1 small" onclick="rejectPartnerVerification(${p.id})">
              <i class="bi bi-x-circle me-1"></i> Tolak
            </button>
            <button class="btn btn-sm btn-success flex-fill py-1 small fw-semibold" onclick="approvePartnerVerification(${p.id})">
              <i class="bi bi-check-circle me-1"></i> Setujui
            </button>
          </div>
        </div>
      `;
    }).join('');

  } catch (err) {
    container.innerHTML = `<div class="alert alert-danger small p-2">${err.message}</div>`;
  }
}

async function approvePartnerVerification(partnerId) {
  try {
    await ApiClient.post(`/admin/partners/${partnerId}/verify`, { note: 'Dokumen dan keahlian telah disetujui admin.' });
    ApiClient.showToast('Mitra Disetujui', 'Mitra teknisi telah aktif dan dapat menerima pesanan online.', 'success');
    loadPendingVerifications();
    loadAdminDashboard();
  } catch (err) {
    ApiClient.showToast('Gagal Menyetujui', err.message, 'danger');
  }
}

async function rejectPartnerVerification(partnerId) {
  const reason = prompt('Masukkan alasan penolakan verifikasi mitra:', 'Dokumen pendukung kurang lengkap');
  if (!reason) return;

  try {
    await ApiClient.post(`/admin/partners/${partnerId}/reject`, { reason });
    ApiClient.showToast('Mitra Ditolak', 'Permohonan mitra telah ditolak.', 'info');
    loadPendingVerifications();
    loadAdminDashboard();
  } catch (err) {
    ApiClient.showToast('Gagal Menolak', err.message, 'danger');
  }
}

/**
 * 3. User Moderation (Activate / Suspend)
 */
async function loadAdminUsers() {
  const tbody = document.getElementById('admin-users-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div> <span class="small text-secondary">Memuat daftar pengguna...</span></td></tr>`;

  try {
    const res = await ApiClient.get('/admin/users');
    const rawData = res.data?.data || res.data || [];
    const users = Array.isArray(rawData) ? rawData : [];

    if (users.length === 0) {
      tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-muted small">Tidak ada data pengguna.</td></tr>`;
      return;
    }

    tbody.innerHTML = users.slice(0, 15).map(u => {
      const isSuspended = u.status === 'suspended';
      const roleBadge = u.role === 'admin' ? 'bg-danger' : (u.role === 'partner' ? 'bg-success' : 'bg-primary');

      return `
        <tr>
          <td>
            <div class="d-flex align-items-center gap-2">
              <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.75rem;">
                ${(u.name || 'U').substring(0, 2).toUpperCase()}
              </div>
              <div>
                <strong class="d-block text-dark small">${u.name}</strong>
                <span class="text-muted" style="font-size: 0.72rem;">${u.email}</span>
              </div>
            </div>
          </td>
          <td><span class="small text-secondary">${u.phone || '-'}</span></td>
          <td><span class="badge ${roleBadge} small text-uppercase" style="font-size: 0.68rem;">${u.role}</span></td>
          <td>
            <span class="badge ${isSuspended ? 'bg-danger' : 'bg-success-subtle text-success'} small">
              ${u.status}
            </span>
          </td>
          <td class="text-end">
            ${u.role !== 'admin' ? `
              <button class="btn btn-sm ${isSuspended ? 'btn-outline-success' : 'btn-outline-danger'} py-1 px-2.5 small" onclick="toggleUserStatus(${u.id}, '${isSuspended ? 'active' : 'suspended'}')">
                ${isSuspended ? '<i class="bi bi-unlock me-1"></i>Aktifkan' : '<i class="bi bi-lock me-1"></i>Suspend'}
              </button>
            ` : '<span class="text-muted small fst-italic">Sistem</span>'}
          </td>
        </tr>
      `;
    }).join('');

  } catch (err) {
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger small">${err.message}</td></tr>`;
  }
}

async function toggleUserStatus(userId, newStatus) {
  const confirmMsg = newStatus === 'suspended'
    ? 'Apakah Anda yakin ingin menangguhkan (suspend) akun pengguna ini?'
    : 'Aktifkan kembali akun pengguna ini?';
  
  if (!confirm(confirmMsg)) return;

  try {
    await ApiClient.patch(`/admin/users/${userId}/status`, { status: newStatus });
    ApiClient.showToast('Status Diperbarui', `Akun pengguna berhasil diubah ke status: ${newStatus.toUpperCase()}`, 'success');
    loadAdminUsers();
  } catch (err) {
    ApiClient.showToast('Gagal Mengubah Status', err.message, 'danger');
  }
}

/**
 * 4. Admin All Orders Management
 */
let allTechniciansCache = [];

async function loadAdminOrders(statusFilter = '') {
  const tbody = document.getElementById('admin-orders-table-body');
  if (!tbody) return;

  tbody.innerHTML = `<tr><td colspan="7" class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary"></div> <span class="ms-2 small text-secondary">Memuat seluruh pesanan...</span></td></tr>`;

  try {
    const params = { per_page: 50 };
    if (statusFilter) params.status = statusFilter;

    const urlParams = new URLSearchParams(window.location.search);
    const searchParam = urlParams.get('search');
    if (searchParam) params.search = searchParam;

    const res = await ApiClient.get('/admin/orders', params);
    const orders = res.data?.data || res.data || [];

    // Preload technicians for assignment modal
    if (allTechniciansCache.length === 0) {
      try {
        const techRes = await ApiClient.get('/technicians?per_page=50');
        const rawT = techRes.data?.data || techRes.data || [];
        allTechniciansCache = Array.isArray(rawT) ? rawT : [];
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
      allTechniciansCache.map(t => {
        const spec = t.partner_profile?.skills?.map(s => s.category?.name).join(', ') || 'Teknisi';
        return `<option value="${t.id}">${t.name} — ${spec} (Rating: ${t.partner_profile?.average_rating || 4.9})</option>`;
      }).join('');
  }

  const modalEl = document.getElementById('assignTechnicianModal');
  if (modalEl) {
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  } else {
    const techId = prompt(`Tugaskan Teknisi untuk pesanan #${orderNumber}. Masukkan ID User Teknisi:`, '2');
    if (techId) submitAssignPartner(orderId, techId);
  }
}

async function submitAssignPartner(orderId, partnerId) {
  try {
    await ApiClient.post(`/admin/orders/${orderId}/assign`, { partner_id: partnerId });
    ApiClient.showToast('Penugasan Berhasil', 'Mitra teknisi berhasil ditugaskan ke pesanan ini.', 'success');
    
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
window.loadAdminDashboard = loadAdminDashboard;
window.loadPendingVerifications = loadPendingVerifications;
window.approvePartnerVerification = approvePartnerVerification;
window.rejectPartnerVerification = rejectPartnerVerification;
window.loadAdminUsers = loadAdminUsers;
window.toggleUserStatus = toggleUserStatus;
window.openAssignModal = openAssignModal;
window.submitAssignPartner = submitAssignPartner;
window.adminCancelOrder = adminCancelOrder;
