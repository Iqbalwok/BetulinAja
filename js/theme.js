/**
 * BetulinAja — Unified Dark / Light Theme Engine
 * Uses Bootstrap 5.3 data-bs-theme attribute with LocalStorage persistence.
 * Creators: Iqbal Adityajaya, Maulana Rizki, Sapriadi Sandy (UNIPI)
 */

(function() {
  'use strict';

  const STORAGE_KEY = 'betulinaja_theme';

  function getSavedTheme() {
    return localStorage.getItem(STORAGE_KEY);
  }

  function getSystemPreference() {
    return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem(STORAGE_KEY, theme);
    updateToggleIcons(theme);
  }

  function updateToggleIcons(theme) {
    const toggles = document.querySelectorAll('.btn-theme-toggle');
    toggles.forEach(btn => {
      if (theme === 'dark') {
        btn.innerHTML = '<i class="bi bi-sun-fill text-warning fs-5"></i>';
        btn.setAttribute('title', 'Beralih ke Mode Terang');
        btn.setAttribute('aria-label', 'Beralih ke Mode Terang');
      } else {
        btn.innerHTML = '<i class="bi bi-moon-stars-fill text-secondary fs-5"></i>';
        btn.setAttribute('title', 'Beralih ke Mode Gelap');
        btn.setAttribute('aria-label', 'Beralih ke Mode Gelap');
      }
    });
  }

  // 1. Instant execution to prevent white flash before rendering
  const activeTheme = getSavedTheme() || getSystemPreference();
  document.documentElement.setAttribute('data-bs-theme', activeTheme);

  // 2. Global Toggle API
  window.toggleTheme = function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
    applyTheme(nextTheme);

    if (window.BetulinAja && typeof window.BetulinAja.showToast === 'function') {
      window.BetulinAja.showToast(
        nextTheme === 'dark' ? 'Mode Gelap Aktif' : 'Mode Terang Aktif',
        nextTheme === 'dark' ? 'Tampilan lebih nyaman untuk mata di kondisi minim cahaya.' : 'Tampilan kembali ke tema terang standar.',
        'info',
        2200
      );
    }
  };

  // 3. Setup on DOM ready
  document.addEventListener('DOMContentLoaded', function() {
    const current = document.documentElement.getAttribute('data-bs-theme') || 'light';
    updateToggleIcons(current);

    // Bind existing buttons
    document.querySelectorAll('.btn-theme-toggle').forEach(btn => {
      btn.addEventListener('click', window.toggleTheme);
    });

    // Auto-inject floating theme button if none in navbar
    if (!document.querySelector('.btn-theme-toggle') && !document.getElementById('floating-theme-toggle')) {
      const floatBtn = document.createElement('button');
      floatBtn.id = 'floating-theme-toggle';
      floatBtn.className = 'btn-theme-toggle floating-theme-btn';
      floatBtn.type = 'button';
      floatBtn.title = 'Ubah Tema (Terang / Gelap)';
      floatBtn.innerHTML = current === 'dark' ? '<i class="bi bi-sun-fill text-warning fs-5"></i>' : '<i class="bi bi-moon-stars-fill text-secondary fs-5"></i>';
      floatBtn.addEventListener('click', window.toggleTheme);
      document.body.appendChild(floatBtn);
    }
  });

  // 4. Listen for OS theme changes if user hasn't explicitly set preference
  if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
      if (!getSavedTheme()) {
        applyTheme(e.matches ? 'dark' : 'light');
      }
    });
  }
})();
