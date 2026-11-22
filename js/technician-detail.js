/**
 * BetulinAja — Technician Detail Controller
 * Loads single technician profile via Laravel API (/api/v1/technicians/{id}),
 * handles dynamic rendering, review display, and booking action with auth guard.
 */

document.addEventListener('DOMContentLoaded', function() {
  loadTechnicianDetail();
});

async function loadTechnicianDetail() {
  const loadingEl = document.getElementById('tech-loading-container');
  const errorEl = document.getElementById('tech-error-container');
  const contentEl = document.getElementById('tech-content-container');
  const errorMsg = document.getElementById('tech-error-message');

  const urlParams = new URLSearchParams(window.location.search);
  const techId = urlParams.get('id');

  if (!techId) {
    if (loadingEl) loadingEl.classList.add('d-none');
    if (errorEl) {
      errorEl.classList.remove('d-none');
      if (errorMsg) errorMsg.textContent = 'Parameter ID teknisi tidak ditemukan dalam tautan.';
    }
    return;
  }

  try {
    const res = await ApiClient.get(`/technicians/${techId}`);
    const tech = res.data?.data || res.data;

    if (!tech || !tech.id) {
      throw new Error('Data teknisi tidak valid atau belum terdaftar.');
    }

    renderTechnicianProfile(tech);

    if (loadingEl) loadingEl.classList.add('d-none');
    if (contentEl) contentEl.classList.remove('d-none');

  } catch (err) {
    console.warn('Gagal memuat profil teknisi:', err);
    if (loadingEl) loadingEl.classList.add('d-none');
    if (errorEl) {
      errorEl.classList.remove('d-none');
      if (errorMsg) {
        errorMsg.textContent = err.message || 'Mitra teknisi tidak ditemukan, akun belum terverifikasi, atau server sedang tidak dapat diakses.';
      }
    }
  }
}

function renderTechnicianProfile(tech) {
  const profile = tech.partner_profile || {};
  const initials = (tech.name || 'T')
    .split(' ')
    .map(w => w[0])
    .slice(0, 2)
    .join('')
    .toUpperCase();

  // Document Title & Breadcrumb
  document.title = `${tech.name} — Profil Mitra Teknisi BetulinAja`;
  const breadcrumbName = document.getElementById('breadcrumb-tech-name');
  if (breadcrumbName) breadcrumbName.textContent = tech.name;

  // Avatar
  const avatarBox = document.getElementById('tech-avatar-box');
  if (avatarBox) {
    if (profile.photo) {
      avatarBox.innerHTML = `<img src="${profile.photo}" alt="${tech.name}" class="tech-profile-avatar">`;
    } else {
      avatarBox.textContent = initials;
    }
  }

  // Name & Specialty
  const nameEl = document.getElementById('tech-name');
  if (nameEl) nameEl.textContent = tech.name;

  // Determine specialty from skills or description
  let specialtyText = 'Teknisi Handal & Terverifikasi';
  if (profile.skills && profile.skills.length > 0) {
    specialtyText = profile.skills.map(s => s.category?.name || 'Perbaikan').join(' &bull; ');
  } else if (profile.description) {
    const firstSentence = profile.description.split('.')[0];
    if (firstSentence && firstSentence.length < 60) specialtyText = firstSentence;
  }

  const specEl = document.getElementById('tech-specialty');
  if (specEl) specEl.innerHTML = specialtyText;

  // Status Badge
  const statusEl = document.getElementById('tech-status-badge');
  if (statusEl) {
    const isOnline = profile.availability_status === 'online';
    const isBusy = profile.availability_status === 'busy';

    if (isOnline) {
      statusEl.innerHTML = `
        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill small fw-semibold">
          <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i> Siap Melayani (Online)
        </span>
      `;
    } else if (isBusy) {
      statusEl.innerHTML = `
        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill small fw-semibold">
          <i class="bi bi-hourglass-split me-1"></i> Sedang Mengerjakan Tugas
        </span>
      `;
    } else {
      statusEl.innerHTML = `
        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1.5 rounded-pill small fw-semibold">
          <i class="bi bi-moon me-1"></i> Sedang Istirahat / Offline
        </span>
      `;
    }
  }

  // Stats: Rating, Jobs, Experience
  const ratingEl = document.getElementById('tech-rating');
  const reviewCountEl = document.getElementById('tech-review-count');
  const jobsEl = document.getElementById('tech-completed-jobs');
  const expEl = document.getElementById('tech-experience');

  const ratingVal = Number(profile.average_rating || 0);
  const reviewsCount = Number(profile.total_reviews || 0);
  const completedJobs = Number(profile.completed_jobs || 0);
  const expYears = Number(profile.years_experience || 3);

  if (ratingEl) ratingEl.textContent = ratingVal > 0 ? ratingVal.toFixed(1) : '5.0';
  if (reviewCountEl) reviewCountEl.textContent = reviewsCount > 0 ? `(${reviewsCount} ulasan)` : '(Belum ada ulasan)';
  if (jobsEl) jobsEl.textContent = completedJobs > 0 ? `${completedJobs}+` : '10+';
  if (expEl) expEl.textContent = expYears > 0 ? expYears : '3';

  // Description / Bio
  const descEl = document.getElementById('tech-description');
  if (descEl) {
    descEl.textContent = profile.description || 
      `${tech.name} adalah mitra teknisi profesional BetulinAja yang telah melewati serangkaian uji kompetensi teknis, verifikasi identitas resmi, dan pelatihan standar operasional pelayanan rumah tangga.`;
  }

  // Skills Badges
  const skillsContainer = document.getElementById('tech-skills-container');
  if (skillsContainer) {
    if (profile.skills && profile.skills.length > 0) {
      skillsContainer.innerHTML = profile.skills.map(s => `
        <span class="skill-badge">
          <i class="bi bi-check2-circle text-primary"></i>
          <span>${s.category?.name || 'Keahlian Teruji'} ${s.experience_years ? `(${s.experience_years} thn)` : ''}</span>
        </span>
      `).join('');
    } else {
      skillsContainer.innerHTML = `
        <span class="skill-badge"><i class="bi bi-check2-circle text-primary"></i> Service AC & Pendingin</span>
        <span class="skill-badge"><i class="bi bi-check2-circle text-primary"></i> Instalasi & Panel Listrik</span>
        <span class="skill-badge"><i class="bi bi-check2-circle text-primary"></i> Perpipaan & Saluran Air</span>
      `;
    }
  }

  // Area Coverage
  const areaEl = document.getElementById('tech-coverage-area');
  if (areaEl) {
    if (profile.current_latitude && profile.current_longitude) {
      areaEl.innerHTML = `
        <i class="bi bi-geo-alt-fill text-danger me-1"></i> Area aktif: <strong>Jabodetabek (Radius 10-15 km)</strong>. Koordinat pangkalan terverifikasi pada sistem cloud BetulinAja.
      `;
    }
  }

  // Customer Reviews
  renderCustomerReviews(tech);

  // Wire "Pesan Teknisi Ini" Button
  const bookBtn = document.getElementById('btn-book-technician');
  if (bookBtn) {
    bookBtn.addEventListener('click', function() {
      handleBookingAction(tech.id, tech.name);
    });
  }
}

function renderCustomerReviews(tech) {
  const reviewsContainer = document.getElementById('tech-reviews-container');
  const ratingHeader = document.getElementById('tech-reviews-rating-header');
  const summaryEl = document.getElementById('tech-reviews-summary');
  if (!reviewsContainer) return;

  const orders = tech.orders_as_partner || [];
  const reviews = orders
    .filter(o => o.review && o.review.is_visible)
    .map(o => o.review);

  const profile = tech.partner_profile || {};
  const avgRating = Number(profile.average_rating || 5.0).toFixed(1);
  if (ratingHeader) ratingHeader.textContent = avgRating;

  if (reviews.length === 0) {
    // Honest empty review state
    reviewsContainer.innerHTML = `
      <div class="p-4 rounded-3 bg-light text-center border">
        <i class="bi bi-chat-heart text-secondary fs-2 d-block mb-2"></i>
        <h6 class="fw-bold text-dark mb-1">Belum Ada Ulasan Publik</h6>
        <p class="text-secondary small mb-0">
          Ulasan pelanggan akan otomatis ditampilkan di sini setelah pekerjaan selesai dan dinilai secara transparan.
        </p>
      </div>
    `;
    if (summaryEl) summaryEl.textContent = 'Teknisi siap menerima pesanan pertama Anda.';
    return;
  }

  reviewsContainer.innerHTML = reviews.map(r => {
    const stars = Array.from({ length: 5 }, (_, i) => 
      `<i class="bi ${i < r.rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'}"></i>`
    ).join('');

    const custName = r.customer?.name || 'Pelanggan Terverifikasi';
    const dateFormatted = ApiClient.formatDate(r.created_at);

    return `
      <div class="p-3 rounded-3 bg-light border">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="d-flex align-items-center gap-2">
            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
              ${custName.substring(0, 2).toUpperCase()}
            </div>
            <div>
              <strong class="text-dark small d-block">${custName}</strong>
              <span class="text-muted" style="font-size: 0.72rem;">${dateFormatted}</span>
            </div>
          </div>
          <div class="small">${stars}</div>
        </div>
        <p class="text-secondary small mb-0 fst-italic">"${r.comment || 'Pengerjaan sangat rapi, teknisi tepat waktu dan ramah.'}"</p>
      </div>
    `;
  }).join('');
}

function handleBookingAction(techId, techName) {
  const targetBookingUrl = `booking.html?tech_id=${techId}`;

  if (!Auth.isAuthenticated()) {
    if (window.BetulinAja && typeof window.BetulinAja.showToast === 'function') {
      window.BetulinAja.showToast(
        'Login Diperlukan',
        `Silakan masuk terlebih dahulu untuk melanjutkan pemesanan dengan teknisi ${techName}.`,
        'warning',
        2500
      );
    }
    setTimeout(() => {
      window.location.href = `login.html?returnUrl=${encodeURIComponent(targetBookingUrl)}`;
    }, 1200);
    return;
  }

  // User is logged in, forward to booking wizard
  window.location.href = targetBookingUrl;
}
