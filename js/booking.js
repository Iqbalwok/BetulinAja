/**
 * BetulinAja — Real API-Driven Booking Wizard
 * Connects frontend multi-step booking to Laravel REST API:
 * - Dynamic Categories & Services from GET /api/v1/categories & GET /api/v1/services
 * - Dynamic Estimates from POST /api/v1/estimates
 * - Real Order Submission with Multipart damage photos to POST /api/v1/orders
 */

document.addEventListener('DOMContentLoaded', function() {
  if (document.getElementById('booking-wizard-container')) {
    initRealBookingWizard();
  }
});

const realBookingState = {
  currentStep: 1,
  categories: [],
  services: [],
  selectedCategoryId: null,
  selectedServiceId: null,
  selectedService: null,
  difficultyLevel: 'medium', // 'light', 'medium', 'heavy'
  description: '',
  addressText: 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
  landmark: 'Pagar hitam dekat minimarket',
  lat: -6.2297,
  lng: 106.8164,
  assignmentMethod: 'auto', // 'auto' or 'manual'
  selectedPartnerId: null,
  selectedPartnerName: 'Sistem Otomatis (Rekomendasi Teknisi Terdekat)',
  photos: [], // File objects
  photoPreviews: [], // data URLs for preview
  pricing: {
    base_price: 100000,
    distance_km: 5.0,
    travel_fee: 25000,
    difficulty_fee: 25000,
    total_estimate: 150000
  }
};

async function initRealBookingWizard() {
  const nextBtn = document.getElementById('btn-wizard-next');
  const prevBtn = document.getElementById('btn-wizard-prev');

  // 1. Fetch Categories and Services from Backend API
  await loadCategoriesAndServices();

  // 2. Setup Step Controls
  if (nextBtn) {
    nextBtn.addEventListener('click', handleNextStep);
  }
  if (prevBtn) {
    prevBtn.addEventListener('click', handlePrevStep);
  }

  // 3. Difficulty change
  const diffSelect = document.getElementById('difficulty-select');
  if (diffSelect) {
    diffSelect.addEventListener('change', function() {
      const map = { 'ringan': 'light', 'sedang': 'medium', 'berat': 'heavy' };
      realBookingState.difficultyLevel = map[this.value] || this.value;
      calculateRealEstimate();
    });
  }

  // 4. Photo Upload Handling
  setupPhotoDropzone();

  // 5. Geolocation / Map
  setupGeolocation();

  // 6. Preload technicians for manual selection
  loadAvailableTechnicians();

  // Initial calculation
  calculateRealEstimate();
}

async function loadCategoriesAndServices() {
  const catSelect = document.getElementById('category-select');
  const srvSelect = document.getElementById('service-select');

  try {
    const [catRes, srvRes] = await Promise.all([
      ApiClient.get('/categories'),
      ApiClient.get('/services')
    ]);

    realBookingState.categories = catRes.data || [];
    realBookingState.services = srvRes.data || [];

    if (catSelect && realBookingState.categories.length > 0) {
      catSelect.innerHTML = realBookingState.categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('');
      realBookingState.selectedCategoryId = realBookingState.categories[0].id;

      catSelect.addEventListener('change', function() {
        realBookingState.selectedCategoryId = parseInt(this.value);
        updateServicesDropdown();
      });

      updateServicesDropdown();
    }
  } catch (err) {
    console.warn('Fallback to local category list:', err);
  }
}

function updateServicesDropdown() {
  const srvSelect = document.getElementById('service-select');
  if (!srvSelect) return;

  const filtered = realBookingState.services.filter(s => s.category_id === realBookingState.selectedCategoryId);
  const list = filtered.length > 0 ? filtered : realBookingState.services;

  srvSelect.innerHTML = list.map((s, idx) => `
    <option value="${s.id}" data-base="${s.base_price}" ${idx === 0 ? 'selected' : ''}>
      ${s.name} (${ApiClient.formatRupiah(s.base_price)})
    </option>
  `).join('');

  if (list.length > 0) {
    realBookingState.selectedServiceId = list[0].id;
    realBookingState.selectedService = list[0];
    realBookingState.pricing.base_price = parseFloat(list[0].base_price);
  }

  srvSelect.addEventListener('change', function() {
    realBookingState.selectedServiceId = parseInt(this.value);
    realBookingState.selectedService = realBookingState.services.find(s => s.id === realBookingState.selectedServiceId);
    calculateRealEstimate();
  });

  calculateRealEstimate();
}

async function calculateRealEstimate() {
  if (!realBookingState.selectedServiceId) return;

  try {
    const res = await ApiClient.post('/estimates', {
      service_id: realBookingState.selectedServiceId,
      difficulty_level: realBookingState.difficultyLevel,
      latitude: realBookingState.lat,
      longitude: realBookingState.lng
    });

    if (res.success && res.data) {
      realBookingState.pricing = res.data;
      updateEstimateUI(res.data);
    }
  } catch (err) {
    // Local fallback calculation
    const base = realBookingState.selectedService?.base_price || 100000;
    const diffFee = realBookingState.difficultyLevel === 'heavy' ? 60000 : (realBookingState.difficultyLevel === 'medium' ? 25000 : 0);
    const travel = 25000;
    const total = parseFloat(base) + diffFee + travel;

    const data = {
      base_price: parseFloat(base),
      distance_km: 5.0,
      travel_fee: travel,
      difficulty_fee: diffFee,
      total_estimate: total
    };
    realBookingState.pricing = data;
    updateEstimateUI(data);
  }
}

function updateEstimateUI(pricing) {
  const baseEl = document.getElementById('calc-base-price');
  const distEl = document.getElementById('calc-distance');
  const travelEl = document.getElementById('calc-travel-fee');
  const diffEl = document.getElementById('calc-difficulty-fee');
  const totalEl = document.getElementById('calc-total-estimate');

  if (baseEl) baseEl.textContent = ApiClient.formatRupiah(pricing.base_price);
  if (distEl) distEl.textContent = `${pricing.distance_km || 5.0} km`;
  if (travelEl) travelEl.textContent = ApiClient.formatRupiah(pricing.travel_fee);
  if (diffEl) diffEl.textContent = ApiClient.formatRupiah(pricing.difficulty_fee);
  if (totalEl) totalEl.textContent = ApiClient.formatRupiah(pricing.total_estimate);
}

function setupPhotoDropzone() {
  const photoDropzone = document.getElementById('photo-dropzone');
  const photoInput = document.getElementById('photo-upload-input');
  if (!photoDropzone || !photoInput) return;

  photoDropzone.addEventListener('click', () => photoInput.click());

  photoInput.addEventListener('change', function() {
    if (!this.files || this.files.length === 0) return;
    Array.from(this.files).forEach(file => {
      if (realBookingState.photos.length >= 5) {
        ApiClient.showToast('Batas Foto', 'Maksimal 5 foto kerusakan.', 'warning');
        return;
      }
      if (!file.type.startsWith('image/')) {
        ApiClient.showToast('Format Salah', 'Harap unggah gambar JPG/PNG/WebP.', 'danger');
        return;
      }
      realBookingState.photos.push(file);

      const reader = new FileReader();
      reader.onload = (e) => {
        realBookingState.photoPreviews.push(e.target.result);
        renderPhotoPreviews();
      };
      reader.readAsDataURL(file);
    });
  });
}

function renderPhotoPreviews() {
  const grid = document.getElementById('photo-preview-grid');
  if (!grid) return;

  grid.innerHTML = realBookingState.photoPreviews.map((src, idx) => `
    <div class="photo-preview-item position-relative d-inline-block m-1">
      <img src="${src}" class="rounded border shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
      <button type="button" class="btn btn-sm btn-danger rounded-circle position-absolute top-0 end-0 p-0 d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; transform: translate(30%, -30%);" onclick="removeBookingPhoto(${idx})">
        <i class="bi bi-x small"></i>
      </button>
    </div>
  `).join('');
}

function removeBookingPhoto(idx) {
  realBookingState.photos.splice(idx, 1);
  realBookingState.photoPreviews.splice(idx, 1);
  renderPhotoPreviews();
}

function setupGeolocation() {
  const locBtn = document.getElementById('btn-current-location');
  if (locBtn && navigator.geolocation) {
    locBtn.addEventListener('click', function() {
      this.disabled = true;
      this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mendeteksi...';
      navigator.geolocation.getCurrentPosition(
        (pos) => {
          realBookingState.lat = pos.coords.latitude;
          realBookingState.lng = pos.coords.longitude;
          ApiClient.showToast('Lokasi Terdeteksi', `Koordinat: ${realBookingState.lat.toFixed(4)}, ${realBookingState.lng.toFixed(4)}`, 'success');
          locBtn.disabled = false;
          locBtn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Gunakan Lokasi Saat Ini';
          calculateRealEstimate();
        },
        (err) => {
          ApiClient.showToast('Gagal GPS', 'Izinkan akses lokasi browser Anda.', 'warning');
          locBtn.disabled = false;
          locBtn.innerHTML = '<i class="bi bi-crosshair me-1"></i> Gunakan Lokasi Saat Ini';
        }
      );
    });
  }
}

async function loadAvailableTechnicians() {
  const listEl = document.getElementById('technician-list-container');
  if (!listEl) return;

  const urlParams = new URLSearchParams(window.location.search);
  const preselectedTechId = urlParams.get('tech_id');

  try {
    const res = await ApiClient.get('/technicians?per_page=50');
    const rawData = res.data?.data || res.data || [];
    const techs = Array.isArray(rawData) ? rawData : [];

    // Keep the "Auto Recommendation" card, add dynamic cards below
    techs.forEach(t => {
      const p = t.partner_profile || {};
      const card = document.createElement('div');
      const isPreselected = preselectedTechId && String(t.id) === String(preselectedTechId);

      card.className = `card card-custom p-3 mb-2 technician-selection-card border hover-card cursor-pointer ${isPreselected ? 'selected border-primary bg-soft-primary' : ''}`;
      card.dataset.id = t.id;
      card.dataset.name = t.name;
      card.dataset.rating = p.average_rating || 4.9;

      if (isPreselected) {
        realBookingState.assignmentMethod = 'manual';
        realBookingState.selectedPartnerId = t.id;
        realBookingState.selectedPartnerName = t.name;
        // Unselect auto recommendation card
        const autoCard = document.querySelector('.technician-selection-card[data-id="auto"]');
        if (autoCard) autoCard.classList.remove('selected', 'border-primary', 'bg-soft-primary');
        ApiClient.showToast('Teknisi Dipilih', `Pemesanan ini diarahkan untuk teknisi: ${t.name}`, 'info', 3000);
      }

      const ratingVal = Number(p.average_rating || 4.9).toFixed(1);
      const jobsVal = Number(p.completed_jobs || 15);
      const specialty = (p.skills && p.skills.length > 0) 
        ? p.skills.map(s => s.category?.name).filter(Boolean).join(', ') 
        : (p.description ? p.description.split('.')[0] : 'Teknisi Handal');

      card.innerHTML = `
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center gap-3">
            <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
              ${(t.name || 'T').substring(0, 2).toUpperCase()}
            </div>
            <div>
              <h6 class="fw-bold text-dark mb-0">${t.name}</h6>
              <span class="text-secondary small d-block text-truncate" style="max-width: 220px;">${specialty}</span>
              <div class="d-flex align-items-center gap-1 text-warning small">
                <i class="bi bi-star-fill"></i> ${ratingVal} <span class="text-muted">(${jobsVal}+ order)</span>
              </div>
            </div>
          </div>
          <span class="badge bg-success-subtle text-success small px-2.5 py-1">Online</span>
        </div>
      `;

      card.addEventListener('click', function() {
        document.querySelectorAll('.technician-selection-card').forEach(c => c.classList.remove('selected', 'border-primary', 'bg-soft-primary'));
        this.classList.add('selected', 'border-primary', 'bg-soft-primary');
        realBookingState.assignmentMethod = 'manual';
        realBookingState.selectedPartnerId = t.id;
        realBookingState.selectedPartnerName = t.name;
        ApiClient.showToast('Teknisi Dipilih', `Anda memilih: ${t.name}`, 'info');
      });

      listEl.appendChild(card);
    });
  } catch (e) {
    console.warn('Gagal memuat teknisi untuk booking wizard:', e);
  }
}

function handleNextStep() {
  if (realBookingState.currentStep === 1) {
    const desc = document.getElementById('problem-description');
    if (!desc || !desc.value.trim() || desc.value.trim().length < 10) {
      ApiClient.showToast('Deskripsi Kurang Lengkap', 'Jelaskan kerusakan minimal 10 karakter.', 'warning');
      if (desc) desc.focus();
      return;
    }
    realBookingState.description = desc.value.trim();
  }

  if (realBookingState.currentStep === 2) {
    const addr = document.getElementById('address-input');
    if (!addr || !addr.value.trim()) {
      ApiClient.showToast('Alamat Wajib Diisi', 'Masukkan alamat lengkap lokasi perbaikan.', 'warning');
      if (addr) addr.focus();
      return;
    }
    realBookingState.addressText = addr.value.trim();
    const lmark = document.getElementById('address-notes');
    if (lmark) realBookingState.landmark = lmark.value.trim();
  }

  if (realBookingState.currentStep === 5) {
    submitRealOrder();
    return;
  }

  realBookingState.currentStep++;
  renderStepView();
}

function handlePrevStep() {
  if (realBookingState.currentStep > 1) {
    realBookingState.currentStep--;
    renderStepView();
  }
}

function renderStepView() {
  // Toggle panes
  document.querySelectorAll('.wizard-step-pane').forEach((pane, idx) => {
    pane.classList.toggle('d-none', (idx + 1) !== realBookingState.currentStep);
  });

  // Toggle pills
  document.querySelectorAll('.booking-step-item').forEach((item, idx) => {
    const num = idx + 1;
    item.classList.remove('active', 'completed');
    if (num < realBookingState.currentStep) item.classList.add('completed');
    else if (num === realBookingState.currentStep) item.classList.add('active');
  });

  const prevBtn = document.getElementById('btn-wizard-prev');
  const nextBtn = document.getElementById('btn-wizard-next');

  if (prevBtn) prevBtn.classList.toggle('d-none', realBookingState.currentStep === 1);
  if (nextBtn) {
    if (realBookingState.currentStep === 5) {
      nextBtn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Konfirmasi & Buat Pesanan Nyata';
      nextBtn.classList.remove('btn-primary-custom');
      nextBtn.classList.add('btn-success');
      populateConfirmationSummary();
    } else {
      nextBtn.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-1"></i>';
      nextBtn.classList.add('btn-primary-custom');
      nextBtn.classList.remove('btn-success');
    }
  }

  window.scrollTo({ top: 100, behavior: 'smooth' });
}

function populateConfirmationSummary() {
  const setEl = (id, txt) => {
    const el = document.getElementById(id);
    if (el) el.textContent = txt;
  };

  const srvName = realBookingState.selectedService?.name || 'Service AC';
  setEl('summary-service', srvName);
  setEl('summary-description', realBookingState.description);
  setEl('summary-address', realBookingState.addressText);
  setEl('summary-technician', realBookingState.selectedPartnerName);
  setEl('summary-total', ApiClient.formatRupiah(realBookingState.pricing.total_estimate));
}

/**
 * Submit Real Order to POST /api/v1/orders
 */
async function submitRealOrder() {
  // 1. Ensure authenticated as customer
  if (!Auth.isAuthenticated()) {
    ApiClient.showToast('Perlu Masuk', 'Silakan masuk atau buat akun untuk menyimpan pesanan Anda.', 'warning');
    setTimeout(() => {
      window.location.href = `login.html?returnUrl=${encodeURIComponent(window.location.pathname)}`;
    }, 1500);
    return;
  }

  // Check email verification status
  const currentUser = Auth.getUser();
  if (currentUser && !currentUser.email_verified_at && currentUser.role === 'customer') {
    ApiClient.showToast('Verifikasi Diperlukan', 'Email Anda belum diverifikasi. Silakan lakukan verifikasi OTP terlebih dahulu.', 'warning');
    setTimeout(() => {
      window.location.href = `verify-email.html?email=${encodeURIComponent(currentUser.email)}`;
    }, 1500);
    return;
  }

  const nextBtn = document.getElementById('btn-wizard-next');
  if (nextBtn) {
    nextBtn.disabled = true;
    nextBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses Pesanan ke Cloud...';
  }

  try {
    const formData = new FormData();
    formData.append('service_id', realBookingState.selectedServiceId || 1);
    formData.append('problem_description', realBookingState.description);
    formData.append('address_text', realBookingState.addressText);
    formData.append('landmark', realBookingState.landmark || '');
    formData.append('latitude', realBookingState.lat);
    formData.append('longitude', realBookingState.lng);
    formData.append('difficulty_level', realBookingState.difficultyLevel);
    formData.append('assignment_method', realBookingState.assignmentMethod);

    if (realBookingState.assignmentMethod === 'manual' && realBookingState.selectedPartnerId) {
      formData.append('partner_id', realBookingState.selectedPartnerId);
    }

    // Append photo files
    realBookingState.photos.forEach(file => {
      formData.append('photos[]', file);
    });

    const res = await ApiClient.post('/orders', formData);

    if (res.success && res.data) {
      const order = res.data;
      ApiClient.showToast('Pesanan Berhasil!', `Nomor Pesanan: #${order.order_number}. Mengarahkan ke Live Tracking...`, 'success', 3000);

      // Save active order ID
      localStorage.setItem(CONFIG.STORAGE_KEYS.ACTIVE_ORDER, order.id);

      setTimeout(() => {
        window.location.href = `tracking.html?id=${order.id}`;
      }, 1500);
    }
  } catch (err) {
    ApiClient.showToast('Gagal Membuat Pesanan', err.message, 'danger');
    if (nextBtn) {
      nextBtn.disabled = false;
      nextBtn.innerHTML = '<i class="bi bi-check2-circle me-1"></i> Konfirmasi & Buat Pesanan Nyata';
    }
  }
}

window.removeBookingPhoto = removeBookingPhoto;
