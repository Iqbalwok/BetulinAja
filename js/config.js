/**
 * BetulinAja — Frontend Environment & Configuration
 * Provides dynamic API base URL detection with support for local dev, Vercel deployments,
 * Vite environment variables (VITE_API_URL), and custom overrides.
 */

const CONFIG = (function() {
  function normalizeApiUrl(url) {
    if (!url) return 'https://betulinaja-production.up.railway.app/api/v1';
    let clean = url.trim().replace(/\/+$/, '');
    if (!clean.endsWith('/api/v1')) {
      if (clean.endsWith('/api')) {
        clean += '/v1';
      } else {
        clean += '/api/v1';
      }
    }
    return clean;
  }

  // Priority order for API Base URL:
  // 1. Explicit user override in localStorage
  // 2. Vercel / Vite Environment Variable (window.__ENV__?.VITE_API_URL, window.VITE_API_URL)
  // 3. Localhost detection (http://127.0.0.1:8000/api/v1)
  // 4. Production Cloud Backend (https://betulinaja-production.up.railway.app/api/v1)

  const savedBase = localStorage.getItem('betulinaja_api_base_url');

  let envApiUrl = null;
  if (typeof window !== 'undefined') {
    envApiUrl = window.__ENV__?.VITE_API_URL || 
                window.__ENV__?.API_BASE_URL || 
                window.VITE_API_URL || 
                window.API_BASE_URL || 
                null;
  }

  const isLocal = typeof window !== 'undefined' && (
    window.location.hostname === 'localhost' || 
    window.location.hostname === '127.0.0.1' ||
    window.location.protocol === 'file:'
  );

  let finalApiUrl;
  if (savedBase) {
    finalApiUrl = normalizeApiUrl(savedBase);
  } else if (envApiUrl) {
    finalApiUrl = normalizeApiUrl(envApiUrl);
  } else if (isLocal) {
    finalApiUrl = 'http://127.0.0.1:8000/api/v1';
  } else {
    // Production Cloud Backend on Railway
    finalApiUrl = 'https://betulinaja-production.up.railway.app/api/v1';
  }

  return {
    API_BASE_URL: finalApiUrl,
    APP_NAME: 'BetulinAja',
    APP_VERSION: '1.2.0',
    STORAGE_KEYS: {
      TOKEN: 'betulinaja_token',
      USER: 'betulinaja_user',
      ROLE: 'betulinaja_role',
      ACTIVE_ORDER: 'betulinaja_active_order',
    },
    SET_API_BASE_URL: function(url) {
      if (url) {
        localStorage.setItem('betulinaja_api_base_url', normalizeApiUrl(url));
      } else {
        localStorage.removeItem('betulinaja_api_base_url');
      }
      window.location.reload();
    }
  };
})();

window.CONFIG = CONFIG;
