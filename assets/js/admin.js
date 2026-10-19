/**
 * BetulinAja — Admin Portal JavaScript
 * Chart.js initializations, pricing manager simulator, filter & search interactions
 */

document.addEventListener('DOMContentLoaded', function() {
  initAdminCharts();
  initPricingSimulator();
  initAdminTableFilters();
});

// 1. Chart.js Dashboard Charts
function initAdminCharts() {
  // Orders & Revenue Chart
  const ordersChartEl = document.getElementById('adminOrdersChart');
  if (ordersChartEl && typeof Chart !== 'undefined') {
    const ctx = ordersChartEl.getContext('2d');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [
          {
            label: 'Pesanan Selesai',
            data: [28, 35, 42, 38, 55, 68, 62],
            borderColor: '#2563EB',
            backgroundColor: 'rgba(37, 99, 235, 0.08)',
            fill: true,
            tension: 0.35,
            borderWidth: 2.5,
            pointBackgroundColor: '#2563EB',
            pointRadius: 4
          },
          {
            label: 'Pesanan Dibatalkan',
            data: [2, 3, 1, 4, 2, 3, 2],
            borderColor: '#EF4444',
            backgroundColor: 'transparent',
            borderDash: [5, 5],
            tension: 0.35,
            borderWidth: 2,
            pointRadius: 3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
            labels: { font: { family: 'Inter', size: 12 }, usePointStyle: true }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: { color: '#F1F5F9' },
            ticks: { font: { family: 'Inter' } }
          },
          x: {
            grid: { display: false },
            ticks: { font: { family: 'Inter' } }
          }
        }
      }
    });
  }

  // Category Distribution Doughnut Chart
  const categoryChartEl = document.getElementById('adminCategoryChart');
  if (categoryChartEl && typeof Chart !== 'undefined') {
    const ctx = categoryChartEl.getContext('2d');
    new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['Service AC', 'Elektronik', 'Listrik', 'Plumbing', 'Kulkas', 'Lainnya'],
        datasets: [{
          data: [42, 22, 15, 12, 6, 3],
          backgroundColor: [
            '#2563EB',
            '#10B981',
            '#F59E0B',
            '#0EA5E9',
            '#8B5CF6',
            '#94A3B8'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '72%',
        plugins: {
          legend: {
            position: 'bottom',
            labels: { font: { family: 'Inter', size: 11 }, usePointStyle: true, boxWidth: 8 }
          }
        }
      }
    });
  }
}

// 2. Pricing Configuration Interactive Simulator
function initPricingSimulator() {
  const baseRateInput = document.getElementById('pricing-base-rate');
  const perKmRateInput = document.getElementById('pricing-km-rate');
  const difficultyRateInput = document.getElementById('pricing-difficulty-rate');
  const savePricingBtn = document.getElementById('btn-save-pricing');

  // Preview elements
  const simDistance = document.getElementById('sim-sample-distance');
  const simTotal = document.getElementById('sim-calculated-total');

  function calculatePreview() {
    if (!baseRateInput || !perKmRateInput || !difficultyRateInput || !simTotal) return;
    const base = parseFloat(baseRateInput.value) || 0;
    const perKm = parseFloat(perKmRateInput.value) || 0;
    const diff = parseFloat(difficultyRateInput.value) || 0;
    const distance = 5; // standard 5km test sample

    const total = base + (perKm * distance) + diff;
    simTotal.textContent = BetulinAja.formatRupiah(total);
  }

  [baseRateInput, perKmRateInput, difficultyRateInput].forEach(el => {
    if (el) el.addEventListener('input', calculatePreview);
  });

  if (savePricingBtn) {
    savePricingBtn.addEventListener('click', function() {
      const originalText = savePricingBtn.innerHTML;
      savePricingBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';
      savePricingBtn.disabled = true;

      setTimeout(() => {
        savePricingBtn.innerHTML = originalText;
        savePricingBtn.disabled = false;
        BetulinAja.showToast('Pengaturan Tarif Disimpan', 'Konfigurasi tarif baru telah diterapkan ke simulator demo.', 'success');
      }, 700);
    });
  }
}

// 3. Table Filters & Quick Search
function initAdminTableFilters() {
  const searchInput = document.getElementById('admin-table-search');
  if (searchInput) {
    searchInput.addEventListener('keyup', function() {
      const query = this.value.toLowerCase();
      const rows = document.querySelectorAll('.filterable-table tbody tr');
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
      });
    });
  }

  const statusFilter = document.getElementById('admin-status-filter');
  if (statusFilter) {
    statusFilter.addEventListener('change', function() {
      const selectedStatus = this.value.toLowerCase();
      const rows = document.querySelectorAll('.filterable-table tbody tr');
      rows.forEach(row => {
        if (!selectedStatus || selectedStatus === 'all') {
          row.style.display = '';
        } else {
          const rowStatus = row.dataset.status ? row.dataset.status.toLowerCase() : '';
          row.style.display = rowStatus.includes(selectedStatus) ? '' : 'none';
        }
      });
    });
  }
}
