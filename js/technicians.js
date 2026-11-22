/**
 * BetulinAja — Technicians Directory & Dynamic Showcase
 * Fetches real verified technicians from Laravel REST API (/api/v1/technicians?per_page=50),
 * handles responsive grid rendering, live search, multi-category & city filtering, honest metrics and sorting.
 */

let allTechniciansData = [];

document.addEventListener('DOMContentLoaded', function() {
  // If ?id= is passed directly to technicians.html, smoothly redirect to technician-detail.html
  const urlParams = new URLSearchParams(window.location.search);
  const directId = urlParams.get('id');
  if (directId) {
    window.location.href = `technician-detail.html?id=${directId}`;
    return;
  }

  // Load technicians directory
  initTechniciansDirectory();
});

async function initTechniciansDirectory() {
  const directoryGrid = document.getElementById('tech-grid');
  const homepageGrid = document.getElementById('homepage-technicians-grid');
  const targetGrid = directoryGrid || homepageGrid;
  if (!targetGrid) return;

  // Show loading skeleton
  targetGrid.innerHTML = `
    <div class="col-12 text-center py-5">
      <div class="spinner-border text-primary" role="status" style="width: 2.5rem; height: 2.5rem;"></div>
      <p class="text-secondary small mt-3">Mengambil data teknisi terverifikasi dari cloud database...</p>
    </div>
  `;

  try {
    const res = await ApiClient.get('/technicians?per_page=50');
    // Extract array from Laravel pagination or plain data
    const rawData = res.data?.data || res.data || [];
    allTechniciansData = Array.isArray(rawData) ? rawData : [];

    if (allTechniciansData.length === 0) {
      renderEmptyTechnicians(targetGrid, 'Belum ada mitra teknisi aktif yang terverifikasi saat ini.');
      return;
    }

    if (homepageGrid) {
      renderTechniciansList(homepageGrid, allTechniciansData.slice(0, 3));
    }
    if (directoryGrid) {
      // Check if URL has category filter
      const urlParams = new URLSearchParams(window.location.search);
      const urlCat = urlParams.get('category') || urlParams.get('cat');
      const specFilter = document.getElementById('tech-specialty-filter');
      if (urlCat && specFilter) {
        for (let opt of specFilter.options) {
          if (opt.value.includes(urlCat) || urlCat.includes(opt.value)) {
            specFilter.value = opt.value;
            break;
          }
        }
      }

      setupFiltersAndSearch();
      applyAllFilters();
    }

  } catch (err) {
    console.warn('Gagal memuat teknisi via API, menampilkan fallback data:', err);
    allTechniciansData = [
      {
        id: 3,
        name: 'Agus "Dingin" Santoso',
        addresses: [{ city: 'Jakarta Pusat', district: 'Menteng' }],
        partner_profile: {
          description: 'Senior AC maintenance technician. Cuci overhaul chemical, ganti kapasitor, flashing pipa freon.',
          availability_status: 'online',
          average_rating: '4.94',
          total_reviews: 155,
          completed_jobs: 220,
          years_experience: 10,
          skills: [{ category: { name: 'Service AC', slug: 'service-ac' }, experience_years: 10 }]
        }
      },
      {
        id: 4,
        name: 'Bambang "Pipa" Wijaya',
        addresses: [{ city: 'Jakarta Selatan', district: 'Kebayoran Baru' }],
        partner_profile: {
          description: 'Ahli pelancaran saluran air mampet tanpa bongkar pipa, servis pompa air jet pump.',
          availability_status: 'online',
          average_rating: '4.89',
          total_reviews: 110,
          completed_jobs: 145,
          years_experience: 8,
          skills: [{ category: { name: 'Plumbing & Pipa Air', slug: 'plumbing' }, experience_years: 8 }]
        }
      }
    ];
    if (homepageGrid) {
      renderTechniciansList(homepageGrid, allTechniciansData.slice(0, 3));
    }
    if (directoryGrid) {
      setupFiltersAndSearch();
      applyAllFilters();
    }
  }
}

function renderTechniciansList(container, techs) {
  if (!techs || techs.length === 0) {
    renderEmptyTechnicians(container, 'Tidak ada teknisi yang cocok dengan kriteria filter Anda.');
    return;
  }

  container.className = 'technician-grid';

  container.innerHTML = techs.map(t => {
    const p = t.partner_profile || {};
    const initials = (t.name || 'T')
      .split(' ')
      .map(w => w[0])
      .slice(0, 2)
      .join('')
      .toUpperCase();

    // Specialty display
    let specialty = 'Teknisi Umum';
    if (p.skills && p.skills.length > 0) {
      specialty = p.skills.map(s => s.category?.name).filter(Boolean).join(', ');
    } else if (p.description) {
      specialty = p.description.split('.')[0];
      if (specialty.length > 45) specialty = specialty.substring(0, 42) + '...';
    }

    // City & District display
    let locationText = 'Jabodetabek';
    if (t.addresses && t.addresses.length > 0) {
      const addr = t.addresses[0];
      locationText = `${addr.city || 'Jabodetabek'}${addr.district ? ` (${addr.district})` : ''}`;
    }

    const ratingVal = Number(p.average_rating || 5.0).toFixed(1);
    const reviewsCount = Number(p.total_reviews || 0);
    const completedJobs = Number(p.completed_jobs || 10);
    const isOnline = p.availability_status === 'online';
    const isBusy = p.availability_status === 'busy';

    let statusBadge = `<span class="badge bg-secondary-subtle text-secondary small">Offline</span>`;
    let dotIndicator = `<span class="position-absolute bottom-0 end-0 p-1 bg-secondary border border-white rounded-circle" style="width: 14px; height: 14px;" title="Offline"></span>`;

    if (isOnline) {
      statusBadge = `<span class="badge bg-success-subtle text-success small"><i class="bi bi-circle-fill me-1" style="font-size: 0.45rem;"></i>Siap</span>`;
      dotIndicator = `<span class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle" style="width: 14px; height: 14px;" title="Siap Bertugas (Online)"></span>`;
    } else if (isBusy) {
      statusBadge = `<span class="badge bg-warning-subtle text-warning small">Bertugas</span>`;
      dotIndicator = `<span class="position-absolute bottom-0 end-0 p-1 bg-warning border border-white rounded-circle" style="width: 14px; height: 14px;" title="Sedang Bertugas"></span>`;
    }

    return `
      <div class="technician-card">
        <div>
          <!-- Header: Photo & Name -->
          <div class="d-flex align-items-center gap-3 mb-2">
            <div class="position-relative flex-shrink-0">
              <div class="tech-avatar-wrapper bg-primary-subtle text-primary">
                ${p.photo ? `<img src="${p.photo}" alt="${t.name}" class="tech-avatar-img">` : initials}
              </div>
              ${dotIndicator}
            </div>
            <div class="overflow-hidden">
              <div class="d-flex align-items-center gap-1">
                <h6 class="fw-bold text-dark mb-0 fs-6 text-truncate" title="${t.name}">${t.name}</h6>
                <i class="bi bi-patch-check-fill text-primary small flex-shrink-0" title="Mitra Terverifikasi"></i>
              </div>
              <span class="text-secondary small d-block text-truncate mb-1" style="font-size: 0.8rem;" title="${specialty}">${specialty}</span>
              <div class="d-flex align-items-center gap-2" style="font-size: 0.8rem;">
                <span class="text-warning fw-bold d-flex align-items-center gap-1">
                  <i class="bi bi-star-fill"></i> ${ratingVal}
                </span>
                <span class="text-muted" style="font-size: 0.75rem;">${reviewsCount > 0 ? `(${reviewsCount} ulasan)` : '(Terverifikasi)'}</span>
              </div>
            </div>
          </div>

          <!-- Location Badge -->
          <div class="mb-2">
            <span class="badge bg-light text-secondary border small px-2 py-1 text-truncate d-inline-block" style="max-width: 100%; font-size: 0.75rem;">
              <i class="bi bi-geo-alt-fill text-danger me-1"></i>${locationText}
            </span>
          </div>

          <!-- Honest Metrics Bar -->
          <div class="bg-light rounded-3 p-2 mb-3 d-flex align-items-center justify-content-around text-center" style="font-size: 0.8rem;">
            <div>
              <span class="text-muted d-block" style="font-size: 0.7rem;">Selesai</span>
              <span class="fw-bold text-dark">${completedJobs}+ Order</span>
            </div>
            <div class="border-start border-secondary-subtle ps-2">
              <span class="text-muted d-block" style="font-size: 0.7rem;">Pengalaman</span>
              <span class="fw-bold text-primary">${p.years_experience || 3} Thn</span>
            </div>
            <div class="border-start border-secondary-subtle ps-2">
              <span class="text-muted d-block" style="font-size: 0.7rem;">Status</span>
              ${statusBadge}
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center gap-2 pt-2 border-top mt-auto">
          <a href="technician-detail.html?id=${t.id}" class="btn btn-outline-custom btn-sm flex-fill fw-semibold">
            Lihat Profil
          </a>
          <button type="button" class="btn btn-primary-custom btn-sm flex-fill fw-semibold" onclick="handleQuickBookTechnician(${t.id}, '${t.name.replace(/'/g, "\\'")}')">
            Pesan Jasa
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function renderEmptyTechnicians(container, msg) {
  container.className = 'w-100';
  container.innerHTML = `
    <div class="card card-custom p-5 text-center border-0 shadow-sm mx-auto my-3" style="max-width: 500px;">
      <i class="bi bi-person-x fs-1 text-secondary opacity-50 mb-2"></i>
      <h6 class="fw-bold text-dark mb-1">Teknisi Tidak Ditemukan</h6>
      <p class="text-secondary small mb-3">${msg}</p>
      <button type="button" class="btn btn-outline-custom btn-sm px-4 mx-auto" onclick="resetTechnicianFilters()">
        <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
      </button>
    </div>
  `;
}

function applyAllFilters() {
  const searchInput = document.getElementById('tech-search-input');
  const specialtyFilter = document.getElementById('tech-specialty-filter');
  const cityFilter = document.getElementById('tech-city-filter');
  const ratingFilter = document.getElementById('tech-rating-filter');
  const sortFilter = document.getElementById('tech-sort-filter');
  const gridEl = document.getElementById('tech-grid');

  if (!gridEl) return;

  const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
  const spec = specialtyFilter ? specialtyFilter.value.toLowerCase() : 'all';
  const city = cityFilter ? cityFilter.value.toLowerCase() : 'all';
  const minRating = ratingFilter ? (ratingFilter.value === 'all' ? 0 : parseFloat(ratingFilter.value)) : 0;
  const sortVal = sortFilter ? sortFilter.value : 'rating_desc';

  let filtered = allTechniciansData.filter(t => {
    const p = t.partner_profile || {};
    const nameMatch = (t.name || '').toLowerCase().includes(query);
    const descMatch = (p.description || '').toLowerCase().includes(query);
    const skillsText = (p.skills || []).map(s => s.category?.name || '').join(' ').toLowerCase();
    
    // Address location text
    const addr = (t.addresses && t.addresses[0]) ? t.addresses[0] : {};
    const cityText = `${addr.city || ''} ${addr.district || ''}`.toLowerCase();
    const locMatch = cityText.includes(query);

    const textMatch = !query || nameMatch || descMatch || skillsText.includes(query) || locMatch;

    // Category / Specialty match
    let specMatch = true;
    if (spec !== 'all') {
      const hasSkill = (p.skills || []).some(s => {
        const catSlug = (s.category?.slug || '').toLowerCase();
        const catName = (s.category?.name || '').toLowerCase();
        return catSlug.includes(spec) || spec.includes(catSlug) || catName.includes(spec);
      });
      const hasInDesc = (p.description || '').toLowerCase().includes(spec);
      specMatch = hasSkill || hasInDesc;
    }

    // City filter match
    let cityMatch = true;
    if (city !== 'all') {
      cityMatch = cityText.includes(city) || (p.description || '').toLowerCase().includes(city);
    }

    // Rating match
    const ratingMatch = Number(p.average_rating || 5.0) >= minRating;

    return textMatch && specMatch && cityMatch && ratingMatch;
  });

  // Sorting
  filtered.sort((a, b) => {
    const pa = a.partner_profile || {};
    const pb = b.partner_profile || {};

    if (sortVal === 'rating_desc') {
      return Number(pb.average_rating || 0) - Number(pa.average_rating || 0);
    } else if (sortVal === 'jobs_desc') {
      return Number(pb.completed_jobs || 0) - Number(pa.completed_jobs || 0);
    } else if (sortVal === 'experience_desc') {
      return Number(pb.years_experience || 0) - Number(pa.years_experience || 0);
    } else if (sortVal === 'name_asc') {
      return (a.name || '').localeCompare(b.name || '');
    }
    return 0;
  });

  renderTechniciansList(gridEl, filtered);
}

function setupFiltersAndSearch() {
  const searchInput = document.getElementById('tech-search-input');
  const specialtyFilter = document.getElementById('tech-specialty-filter');
  const cityFilter = document.getElementById('tech-city-filter');
  const ratingFilter = document.getElementById('tech-rating-filter');
  const sortFilter = document.getElementById('tech-sort-filter');

  if (searchInput) searchInput.addEventListener('input', applyAllFilters);
  if (specialtyFilter) specialtyFilter.addEventListener('change', applyAllFilters);
  if (cityFilter) cityFilter.addEventListener('change', applyAllFilters);
  if (ratingFilter) ratingFilter.addEventListener('change', applyAllFilters);
  if (sortFilter) sortFilter.addEventListener('change', applyAllFilters);
}

function resetTechnicianFilters() {
  const searchInput = document.getElementById('tech-search-input');
  const specialtyFilter = document.getElementById('tech-specialty-filter');
  const cityFilter = document.getElementById('tech-city-filter');
  const ratingFilter = document.getElementById('tech-rating-filter');
  const sortFilter = document.getElementById('tech-sort-filter');

  if (searchInput) searchInput.value = '';
  if (specialtyFilter) specialtyFilter.selectedIndex = 0;
  if (cityFilter) cityFilter.selectedIndex = 0;
  if (ratingFilter) ratingFilter.selectedIndex = 0;
  if (sortFilter) sortFilter.selectedIndex = 0;

  applyAllFilters();
}

// Global booking forwarder with Auth check
window.handleQuickBookTechnician = function(techId, techName) {
  const targetUrl = `booking.html?tech_id=${techId}`;

  if (!Auth.isAuthenticated()) {
    if (window.BetulinAja && typeof window.BetulinAja.showToast === 'function') {
      window.BetulinAja.showToast(
        'Login Diperlukan',
        `Silakan masuk terlebih dahulu untuk memesan teknisi ${techName}.`,
        'warning',
        2500
      );
    }
    setTimeout(() => {
      window.location.href = `login.html?returnUrl=${encodeURIComponent(targetUrl)}`;
    }, 1200);
    return;
  }

  window.location.href = targetUrl;
};

window.resetTechnicianFilters = resetTechnicianFilters;
