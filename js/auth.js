/**
 * BetulinAja — Authentication & Session Manager
 * Handles login, register, token lifecycle, role-based page guards,
 * and dynamic navbar auth state.
 */

const Auth = (function() {
  function getToken() {
    return localStorage.getItem(CONFIG.STORAGE_KEYS.TOKEN);
  }

  function getUser() {
    const raw = localStorage.getItem(CONFIG.STORAGE_KEYS.USER);
    try {
      return raw ? JSON.parse(raw) : null;
    } catch (e) {
      return null;
    }
  }

  function isAuthenticated() {
    return !!getToken() && !!getUser();
  }

  function getRole() {
    const user = getUser();
    return user ? user.role : null;
  }

  async function login(loginIdentifier, password, deviceName = 'browser_web') {
    const res = await ApiClient.post('/auth/login', {
      login: loginIdentifier,
      password: password,
      device_name: deviceName
    });

    if (res.success && res.data?.token) {
      localStorage.setItem(CONFIG.STORAGE_KEYS.TOKEN, res.data.token);
      localStorage.setItem(CONFIG.STORAGE_KEYS.USER, JSON.stringify(res.data.user));
      localStorage.setItem(CONFIG.STORAGE_KEYS.ROLE, res.data.user.role);
      return res.data;
    }
    throw new Error(res.message || 'Login gagal.');
  }

  async function register(payload) {
    const res = await ApiClient.post('/auth/register', payload);
    if (res.success) {
      if (res.data?.token) {
        localStorage.setItem(CONFIG.STORAGE_KEYS.TOKEN, res.data.token);
        localStorage.setItem(CONFIG.STORAGE_KEYS.USER, JSON.stringify(res.data.user));
        localStorage.setItem(CONFIG.STORAGE_KEYS.ROLE, res.data.user.role);
      }
      return res.data;
    }
    throw new Error(res.message || 'Registrasi gagal.');
  }

  async function logout() {
    try {
      if (getToken()) {
        await ApiClient.post('/auth/logout');
      }
    } catch (err) {
      console.warn('Logout API warning:', err.message);
    } finally {
      localStorage.removeItem(CONFIG.STORAGE_KEYS.TOKEN);
      localStorage.removeItem(CONFIG.STORAGE_KEYS.USER);
      localStorage.removeItem(CONFIG.STORAGE_KEYS.ROLE);
      localStorage.removeItem(CONFIG.STORAGE_KEYS.ACTIVE_ORDER);
      window.location.href = '/login.html';
    }
  }

  function requireAuth(allowedRoles = []) {
    if (!isAuthenticated()) {
      const returnUrl = encodeURIComponent(window.location.pathname + window.location.search);
      window.location.href = `/login.html?returnUrl=${returnUrl}`;
      return false;
    }

    if (allowedRoles.length > 0) {
      const role = getRole();
      if (!allowedRoles.includes(role)) {
        ApiClient.showToast('Akses Ditolak', `Halaman ini hanya untuk ${allowedRoles.join('/')}. Anda adalah ${role}.`, 'danger');
        setTimeout(() => {
          if (role === 'admin') window.location.href = '/admin/dashboard.html';
          else if (role === 'partner') window.location.href = '/partner/dashboard.html';
          else window.location.href = '/index.html';
        }, 1500);
        return false;
      }
    }
    return true;
  }

  function updateNavbarAuth() {
    const authArea = document.getElementById('navbar-auth-controls');
    if (!authArea) return;

    if (isAuthenticated()) {
      const user = getUser();
      const initials = (user.name || 'User')
        .split(' ')
        .map(n => n[0])
        .slice(0, 2)
        .join('')
        .toUpperCase();

      let dashboardUrl = '/order-history.html';
      let roleLabel = 'Pelanggan';
      let badgeClass = 'bg-primary-subtle text-primary';

      if (user.role === 'admin') {
        dashboardUrl = '/admin/dashboard.html';
        roleLabel = 'Admin';
        badgeClass = 'bg-danger-subtle text-danger';
      } else if (user.role === 'partner') {
        dashboardUrl = '/partner/dashboard.html';
        roleLabel = 'Mitra Teknisi';
        badgeClass = 'bg-success-subtle text-success';
      }

      authArea.innerHTML = `
        <div class="dropdown">
          <button class="btn btn-outline-custom btn-sm dropdown-toggle d-flex align-items-center gap-2 py-1 px-2.5 rounded-pill shadow-none" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold" style="width: 28px; height: 28px; font-size: 0.75rem;">
              ${initials}
            </div>
            <span class="d-none d-md-inline-block fw-semibold text-dark text-truncate" style="max-width: 120px; font-size: 0.85rem;">${user.name}</span>
          </button>
          <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 py-2 mt-2" style="min-width: 220px;">
            <li class="px-3 py-2 border-bottom">
              <span class="d-block fw-bold text-dark text-truncate">${user.name}</span>
              <span class="badge ${badgeClass} small" style="font-size: 0.7rem;">${roleLabel}</span>
              <span class="d-block text-muted small mt-1 text-truncate">${user.email || user.phone}</span>
            </li>
            <li>
              <a class="dropdown-item py-2 d-flex align-items-center gap-2 small" href="${dashboardUrl}">
                <i class="bi bi-speedometer2 text-primary"></i>
                <span>${user.role === 'customer' ? 'Pesanan Saya' : 'Dashboard Portal'}</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item py-2 d-flex align-items-center gap-2 small" href="/profile.html">
                <i class="bi bi-person-gear text-secondary"></i>
                <span>Pengaturan Profil</span>
              </a>
            </li>
            <li><hr class="dropdown-divider my-1"></li>
            <li>
              <button class="dropdown-item py-2 d-flex align-items-center gap-2 text-danger small" onclick="Auth.logout()">
                <i class="bi bi-box-arrow-right"></i>
                <span>Keluar (Logout)</span>
              </button>
            </li>
          </ul>
        </div>
      `;
    } else {
      authArea.innerHTML = `
        <a href="/login.html" class="btn btn-outline-custom btn-sm px-3 py-2 d-inline-flex align-items-center gap-1" style="font-size: 0.875rem;">
          <i class="bi bi-box-arrow-in-right"></i>
          <span>Masuk</span>
        </a>
      `;
    }
  }

  // Auto initialize navbar on DOM load
  document.addEventListener('DOMContentLoaded', updateNavbarAuth);

  return {
    getToken,
    getUser,
    getRole,
    isAuthenticated,
    login,
    register,
    logout,
    requireAuth,
    updateNavbarAuth
  };
})();

window.Auth = Auth;
