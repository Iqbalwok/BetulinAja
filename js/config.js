/**
 * BetulinAja — Frontend Environment & Configuration
 * Provides dynamic API base URL detection with support for local dev, Vercel deployments, and custom overrides.
 */

const CONFIG = (function() {
  // Allow overriding via window.__ENV__ or localStorage
  const savedBase = localStorage.getItem('betulinaja_api_base_url');
  
  let defaultApiUrl = 'http://127.0.0.1:8000/api/v1';
  
  // If running on local XAMPP/browser dev
  const isLocal = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';
  
  if (!isLocal) {
    // In production Vercel deployment: can be pointed to Railway/Render/VPS Laravel backend
    defaultApiUrl = window.__ENV__?.API_BASE_URL || 'https://api.betulinaja.id/api/v1';
  }

  return {
    API_BASE_URL: savedBase || (window.__ENV__ && window.__ENV__.API_BASE_URL) || defaultApiUrl,
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
        localStorage.setItem('betulinaja_api_base_url', url.replace(/\/+$/, ''));
      } else {
        localStorage.removeItem('betulinaja_api_base_url');
      }
      window.location.reload();
    }
  };
})();

window.CONFIG = CONFIG;
