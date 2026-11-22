/**
 * BetulinAja — Centralized REST API Client
 * Wraps fetch requests with automatic Bearer token injection, error handling,
 * response parsing, and unified UI feedback.
 */

const ApiClient = (function() {
  function getToken() {
    return localStorage.getItem(CONFIG.STORAGE_KEYS.TOKEN);
  }

  function formatRupiah(amount) {
    if (amount === undefined || amount === null) return 'Rp 0';
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0
    }).format(amount);
  }

  function formatDate(isoString, includeTime = true) {
    if (!isoString) return '-';
    const date = new Date(isoString);
    if (isNaN(date.getTime())) return isoString;

    const options = {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    };
    if (includeTime) {
      options.hour = '2-digit';
      options.minute = '2-digit';
    }
    return date.toLocaleDateString('id-ID', options) + (includeTime ? ' WIB' : '');
  }

  function showToast(title, message, type = 'info', duration = 4000) {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      container.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999; display: flex; flex-direction: column; gap: 10px; max-width: 360px; pointer-events: none;';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'error' ? 'danger' : type} shadow-lg border-0 d-flex align-items-start gap-2 p-3 mb-0`;
    toast.style.cssText = 'pointer-events: auto; animation: slideIn 0.3s ease forwards; border-radius: 12px; font-size: 0.875rem;';

    let icon = 'bi-info-circle-fill';
    if (type === 'success') icon = 'bi-check-circle-fill';
    if (type === 'danger' || type === 'error') icon = 'bi-exclamation-triangle-fill';
    if (type === 'warning') icon = 'bi-exclamation-circle-fill';

    toast.innerHTML = `
      <i class="bi ${icon} fs-5 mt-1 flex-shrink-0"></i>
      <div class="flex-grow-1 overflow-hidden">
        <strong class="d-block text-dark">${title}</strong>
        <div class="text-secondary small mt-0.5" style="word-break: break-word;">${message}</div>
      </div>
      <button type="button" class="btn-close btn-close-sm ms-2 shadow-none" aria-label="Close"></button>
    `;

    toast.querySelector('.btn-close').addEventListener('click', () => {
      toast.remove();
    });

    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentElement) {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
      }
    }, duration);
  }

  async function request(endpoint, options = {}) {
    const url = endpoint.startsWith('http') ? endpoint : `${CONFIG.API_BASE_URL}${endpoint.startsWith('/') ? '' : '/'}${endpoint}`;
    
    const headers = options.headers || {};
    const token = getToken();

    headers['Accept'] = 'application/json';

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    // Only set Content-Type to JSON if not FormData (multipart)
    const isFormData = options.body instanceof FormData;
    if (!isFormData && !headers['Content-Type']) {
      headers['Content-Type'] = 'application/json';
    }

    const config = {
      ...options,
      headers: headers
    };

    try {
      const response = await fetch(url, config);
      const text = await response.text();

      let data;
      try {
        data = text ? JSON.parse(text) : {};
      } catch (err) {
        data = { message: text || 'Terjadi kesalahan format respon dari server.' };
      }

      if (!response.ok) {
        // Handle 401 Unauthorized
        if (response.status === 401) {
          // If token was present and expired, notify and clean
          if (token && !endpoint.includes('/auth/login')) {
            localStorage.removeItem(CONFIG.STORAGE_KEYS.TOKEN);
            localStorage.removeItem(CONFIG.STORAGE_KEYS.USER);
            showToast('Sesi Berakhir', 'Sesi Anda telah berakhir. Silakan login kembali.', 'warning');
          }
        }

        // Parse validation errors if available (422)
        let errorMsg = data.message || `Error ${response.status}: Permintaan gagal diproses.`;
        if (data.errors) {
          const firstErrKey = Object.keys(data.errors)[0];
          if (firstErrKey && data.errors[firstErrKey]?.length) {
            errorMsg = data.errors[firstErrKey][0];
          }
        }

        const error = new Error(errorMsg);
        error.status = response.status;
        error.data = data;
        throw error;
      }

      return data;
    } catch (error) {
      // Network failure / server offline
      if (error.name === 'TypeError' && error.message.includes('fetch')) {
        const netErr = new Error(`Tidak dapat terhubung ke Backend API (${CONFIG.API_BASE_URL}). Pastikan server backend sedang aktif.`);
        netErr.status = 0;
        throw netErr;
      }
      throw error;
    }
  }

  return {
    get: (endpoint, params = {}) => {
      const query = new URLSearchParams(params).toString();
      const url = query ? `${endpoint}?${query}` : endpoint;
      return request(url, { method: 'GET' });
    },
    post: (endpoint, body = {}) => {
      const isFormData = body instanceof FormData;
      return request(endpoint, {
        method: 'POST',
        body: isFormData ? body : JSON.stringify(body)
      });
    },
    put: (endpoint, body = {}) => {
      return request(endpoint, {
        method: 'PUT',
        body: JSON.stringify(body)
      });
    },
    patch: (endpoint, body = {}) => {
      const isFormData = body instanceof FormData;
      return request(endpoint, {
        method: 'PATCH',
        body: isFormData ? body : JSON.stringify(body)
      });
    },
    delete: (endpoint) => {
      return request(endpoint, { method: 'DELETE' });
    },
    formatRupiah,
    formatDate,
    showToast
  };
})();

window.ApiClient = ApiClient;
