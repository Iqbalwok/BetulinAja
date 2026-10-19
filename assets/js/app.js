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
  // 1. Tooltips
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
  tooltipTriggerList.map(function(tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // 2. Inject Floating WhatsApp Widget & Scroll To Top Button jika belum ada
  if (!document.getElementById('floating-wa-btn')) {
    const waWidget = document.createElement('div');
    waWidget.innerHTML = `
      <!-- Scroll To Top Button -->
      <button type="button" class="btn-scroll-top" id="btn-scroll-top" title="Kembali ke atas">
        <i class="bi bi-chevron-up"></i>
      </button>

      <!-- Floating WA Button -->
      <a href="https://wa.me/6285714310516?text=Halo%20BetulinAja,%20saya%20butuh%20bantuan%20jasa%20service%20rumah." 
         target="_blank" 
         class="floating-wa-btn" 
         id="floating-wa-btn" 
         title="Hubungi Kami via WhatsApp (085714310516)">
        <i class="bi bi-whatsapp"></i>
      </a>

      <!-- Social Proof Container (Bottom Left) -->
      <div id="social-proof-container"></div>
    `;
    document.body.appendChild(waWidget);

    // Scroll To Top Handler
    const btnScrollTop = document.getElementById('btn-scroll-top');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 320) {
        btnScrollTop.classList.add('visible');
      } else {
        btnScrollTop.classList.remove('visible');
      }
    });

    btnScrollTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  }

  // 3. Social Proof Engine (Notifikasi Pesanan Live Halus)
  const proofContainer = document.getElementById('social-proof-container');
  if (proofContainer) {
    const sampleProofs = [
      { name: 'Bpk. Hendra', loc: 'Tangerang', service: 'Cuci AC 1/2 - 1 PK', time: 'Baru saja' },
      { name: 'Ibu Maya', loc: 'Jakarta Barat', service: 'Perbaikan Kulkas 2 Pintu', time: '2 menit lalu' },
      { name: 'Bpk. Aris', loc: 'Tangerang Selatan', service: 'Pipa Mampet & Kran Bocor', time: '4 menit lalu' },
      { name: 'Ibu Dian', loc: 'Jakarta Selatan', service: 'Korsleting Listrik Rumah', time: '6 menit lalu' },
      { name: 'Bpk. Rizky', loc: 'Bekasi', service: 'Instalasi TV & Braket Dinding', time: '8 menit lalu' }
    ];

    let proofIndex = 0;
    function triggerNextProof() {
      const data = sampleProofs[proofIndex % sampleProofs.length];
      proofIndex++;

      const toast = document.createElement('div');
      toast.className = 'social-proof-toast';
      toast.innerHTML = `
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
          <i class="bi bi-bell fs-5"></i>
        </div>
        <div class="overflow-hidden">
          <div class="d-flex align-items-center gap-1">
            <strong class="text-dark small">${data.name}</strong>
            <span class="text-muted" style="font-size: 0.72rem;">di ${data.loc}</span>
          </div>
          <div class="text-primary small fw-semibold text-truncate" style="font-size: 0.8rem;">${data.service}</div>
          <span class="text-muted" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i>${data.time}</span>
        </div>
      `;

      proofContainer.innerHTML = '';
      proofContainer.appendChild(toast);

      setTimeout(() => {
        if (toast.parentElement) toast.remove();
      }, 5000);
    }

    // Munculkan notifikasi pertama setelah 5 detik, lalu tiap 20 detik
    setTimeout(() => {
      triggerNextProof();
      setInterval(triggerNextProof, 22000);
    }, 5000);
  }

  // 4. Live Search Autocomplete di Input Hero
  const searchInputs = document.querySelectorAll('input[type="text"][placeholder*="Cari layanan"]');
  const serviceCatalog = [
    { name: 'Cuci AC Split Standard', cat: 'Service AC', price: 'Rp75.000', link: 'booking.html?cat=Service%20AC' },
    { name: 'Bongkar Pasang AC', cat: 'Service AC', price: 'Rp250.000', link: 'booking.html?cat=Service%20AC' },
    { name: 'Perbaikan Korsleting Listrik', cat: 'Listrik', price: 'Rp120.000', link: 'booking.html?cat=Listrik' },
    { name: 'Pipa Wastafel Mampet', cat: 'Plumbing', price: 'Rp150.000', link: 'booking.html?cat=Plumbing' },
    { name: 'Perbaikan Kulkas Tidak Dingin', cat: 'Kulkas', price: 'Rp175.000', link: 'booking.html?cat=Kulkas' },
    { name: 'Install Ulang & Service Komputer', cat: 'Komputer', price: 'Rp100.000', link: 'booking.html?cat=Komputer' },
    { name: 'Perbaikan Mesin Cuci 1 Tabung', cat: 'Elektronik', price: 'Rp150.000', link: 'booking.html?cat=Elektronik' },
  ];

  searchInputs.forEach(input => {
    const parent = input.closest('.position-relative') || input.parentElement;
    if (!parent) return;

    parent.style.position = 'relative';
    const box = document.createElement('div');
    box.className = 'search-suggestion-box';
    parent.appendChild(box);

    input.addEventListener('input', (e) => {
      const query = e.target.value.trim().toLowerCase();
      if (query.length < 2) {
        box.classList.remove('active');
        box.innerHTML = '';
        return;
      }

      const matches = serviceCatalog.filter(s => 
        s.name.toLowerCase().includes(query) || s.cat.toLowerCase().includes(query)
      );

      if (matches.length === 0) {
        box.innerHTML = `<div class="p-3 text-muted small text-center">Layanan tidak ditemukan. <a href="services.html" class="fw-bold text-primary">Lihat semua katalog &rarr;</a></div>`;
        box.classList.add('active');
      } else {
        box.innerHTML = matches.slice(0, 4).map(m => `
          <a href="${m.link}" class="suggestion-item">
            <div>
              <div class="fw-semibold small text-dark">${m.name}</div>
              <span class="badge bg-light text-secondary border" style="font-size: 0.7rem;">${m.cat}</span>
            </div>
            <span class="text-primary fw-bold small">Mulai ${m.price}</span>
          </a>
        `).join('');
        box.classList.add('active');
      }
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!parent.contains(e.target)) {
        box.classList.remove('active');
      }
    });
  });
});

