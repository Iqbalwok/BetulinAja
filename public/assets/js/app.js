/**
 * BetulinAja — Global Core JavaScript
 * Helper functions, toast notification engine, currency formatting
 */

window.BetulinAja = window.BetulinAja || {};

// Format number to Indonesian Rupiah currency string (e.g. Rp150.000)
BetulinAja.formatRupiah = function(number) {
  if (isNaN(number)) return 'Rp0';
  return 'Rp' + Number(number).toLocaleString('id-ID');
};

// Global Toast Notification Engine
BetulinAja.showToast = function(title, message, type = 'info', duration = 4000) {
  let container = document.getElementById('toast-container');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toast-container';
    document.body.appendChild(container);
  }

  const toast = document.createElement('div');
  toast.className = `custom-toast border-start border-4 ${
    type === 'success' ? 'border-success' :
    type === 'danger' ? 'border-danger' :
    type === 'warning' ? 'border-warning' : 'border-primary'
  }`;

  const iconClass = 
    type === 'success' ? 'bi-check-circle-fill text-success' :
    type === 'danger' ? 'bi-x-circle-fill text-danger' :
    type === 'warning' ? 'bi-exclamation-triangle-fill text-warning' : 'bi-info-circle-fill text-primary';

  toast.innerHTML = `
    <i class="bi ${iconClass} fs-4 flex-shrink-0"></i>
    <div class="flex-grow-1">
      <div class="fw-bold text-dark fs-6">${title}</div>
      <div class="small text-secondary">${message}</div>
    </div>
    <button type="button" class="btn-close ms-2 btn-sm flex-shrink-0" aria-label="Close"></button>
  `;

  const closeBtn = toast.querySelector('.btn-close');
  closeBtn.addEventListener('click', () => {
    toast.style.opacity = '0';
    toast.style.transform = 'translateX(100%)';
    setTimeout(() => toast.remove(), 300);
  });

  container.appendChild(toast);

  setTimeout(() => {
    if (toast.parentElement) {
      toast.style.opacity = '0';
      toast.style.transform = 'translateX(100%)';
      setTimeout(() => toast.remove(), 300);
    }
  }, duration);
};

// Initialize Bootstrap Tooltips & Global Handlers
document.addEventListener('DOMContentLoaded', function() {
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});
