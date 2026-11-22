/**
 * BetulinAja — Customer Portal JavaScript
 * Multi-step booking wizard, dynamic cost calculation, Leaflet map integration,
 * photo upload previews, order tracking simulation, and interactive chat demo.
 */

document.addEventListener('DOMContentLoaded', function() {
  initBookingWizard();
  initTrackingPage();
  initChatDemo();
});

// State for multi-step booking
const bookingState = {
  currentStep: 1,
  category: 'Service AC',
  serviceType: 'Cuci AC Standar (0.5 - 1 PK)',
  description: '',
  photos: [],
  address: 'Jl. Sudirman No. 45, Kebayoran Baru, Jakarta Selatan',
  notes: 'Pagar warna hitam, samping minimarket',
  lat: -6.2297,
  lng: 106.8164,
  scheduleType: 'now', // 'now' or 'scheduled'
  scheduleDate: '',
  scheduleTime: '',
  basePrice: 100000,
  distanceKm: 5,
  travelFee: 25000,
  difficulty: 'sedang',
  difficultyFee: 25000,
  totalEstimate: 150000,
  technicianMode: 'auto', // 'auto' or 'manual'
  selectedTechnician: {
    id: 1,
    name: 'Budi Santoso',
    rating: 4.9,
    reviews: 142,
    specialty: 'Master AC & Pendingin',
    distance: '2.4 km',
    eta: '25 Menit',
    photo: 'https://images.unsplash.com/photo-1540569014015-19a7be504e3a?w=150&auto=format&fit=crop&q=80'
  }
};

/**
 * -------------------------------------------------------------
 * 1. Multi-Step Booking Wizard
 * -------------------------------------------------------------
 */
function initBookingWizard() {
  const wizardContainer = document.getElementById('booking-wizard-container');
  if (!wizardContainer) return;

  const nextBtn = document.getElementById('btn-wizard-next');
  const prevBtn = document.getElementById('btn-wizard-prev');
  const photoInput = document.getElementById('photo-upload-input');
  const photoDropzone = document.getElementById('photo-dropzone');
  const categorySelect = document.getElementById('category-select');
  const serviceSelect = document.getElementById('service-select');
  const difficultySelect = document.getElementById('difficulty-select');
  const useLocationBtn = document.getElementById('btn-current-location');

  // Category change mapping
  const serviceOptions = {
    'Service AC': [
      { name: 'Cuci AC Standar (0.5 - 1 PK)', base: 100000 },
      { name: 'Tambah / Isi Freon R32/R410', base: 175000 },
      { name: 'Bongkar Pasang AC', base: 250000 },
      { name: 'Perbaikan AC Bocor / Berisik', base: 150000 }
    ],
    'Elektronik': [
      { name: 'Service TV LED / LCD Mati Total', base: 150000 },
      { name: 'Perbaikan Mesin Cuci 1/2 Tabung', base: 130000 },
      { name: 'Perbaikan Microwave & Oven Listrik', base: 110000 }
    ],
    'Listrik': [
      { name: 'Perbaikan Korsleting Listrik / MCB Drop', base: 125000 },
      { name: 'Pemasangan Stop Kontak & Saklar Baru', base: 75000 },
      { name: 'Instalasi Lampu & Panel Listrik', base: 150000 }
    ],
    'Plumbing': [
      { name: 'Atasi Pipa Saluran Air Mampet', base: 120000 },
      { name: 'Perbaikan Kran Air & Shower Bocor', base: 80000 },
      { name: 'Service Pompa Air / Jet Pump', base: 160000 }
    ],
    'Kulkas': [
      { name: 'Service Kulkas 1/2 Pintu Tidak Dingin', base: 140000 },
      { name: 'Ganti Kompresor Kulkas', base: 220000 },
      { name: 'Pengelasan Kebocoran Pipa Evaporator', base: 180000 }
    ],
    'Komputer': [
      { name: 'Instal Ulang & Optimalisasi OS', base: 100000 },
      { name: 'Pembersihan Thermal Paste & Fan Laptop', base: 90000 },
      { name: 'Ganti Keyboard / Layar LCD Laptop', base: 150000 }
    ]
  };

  if (categorySelect && serviceSelect) {
    categorySelect.addEventListener('change', function() {
      const cat = this.value;
      bookingState.category = cat;
      serviceSelect.innerHTML = '';
      const list = serviceOptions[cat] || serviceOptions['Service AC'];
      list.forEach((item, index) => {
        const opt = document.createElement('option');
        opt.value = item.name;
        opt.textContent = `${item.name} (${BetulinAja.formatRupiah(item.base)})`;
        opt.dataset.base = item.base;
        if (index === 0) opt.selected = true;
        serviceSelect.appendChild(opt);
      });
      bookingState.serviceType = list[0].name;
      bookingState.basePrice = list[0].base;
      updateCostCalculations();
    });

    serviceSelect.addEventListener('change', function() {
      const selectedOpt = serviceSelect.options[serviceSelect.selectedIndex];
      bookingState.serviceType = selectedOpt.value;
      bookingState.basePrice = parseInt(selectedOpt.dataset.base || 100000);
      updateCostCalculations();
    });
  }

  if (difficultySelect) {
    difficultySelect.addEventListener('change', function() {
      bookingState.difficulty = this.value;
      if (this.value === 'ringan') bookingState.difficultyFee = 0;
      else if (this.value === 'sedang') bookingState.difficultyFee = 25000;
      else if (this.value === 'berat') bookingState.difficultyFee = 60000;
      updateCostCalculations();
    });
  }

  // Photo Upload & Preview
  if (photoDropzone && photoInput) {
    photoDropzone.addEventListener('click', () => photoInput.click());

    photoDropzone.addEventListener('dragover', (e) => {
      e.preventDefault();
      photoDropzone.classList.add('dragover');
    });

    photoDropzone.addEventListener('dragleave', () => photoDropzone.classList.remove('dragover'));

    photoDropzone.addEventListener('drop', (e) => {
      e.preventDefault();
      photoDropzone.classList.remove('dragover');
      handleFiles(e.dataTransfer.files);
    });

    photoInput.addEventListener('change', (e) => {
      handleFiles(e.target.files);
    });
  }

  function handleFiles(files) {
    if (!files || files.length === 0) return;
    const maxPhotos = 5;
    Array.from(files).forEach(file => {
      if (bookingState.photos.length >= maxPhotos) {
        BetulinAja.showToast('Batas Foto', 'Maksimal 5 foto kerusakan yang dapat diunggah.', 'warning');
        return;
      }
      if (!file.type.startsWith('image/')) {
        BetulinAja.showToast('Format Tidak Valid', 'Harap unggah file foto (JPG, PNG, WebP).', 'danger');
        return;
      }
      const reader = new FileReader();
      reader.onload = function(e) {
        bookingState.photos.push(e.target.result);
        renderPhotoPreviews();
      };
      reader.readAsDataURL(file);
    });
  }

  function renderPhotoPreviews() {
    const grid = document.getElementById('photo-preview-grid');
    const countIndicator = document.getElementById('photo-count-badge');
    if (!grid) return;

    grid.innerHTML = '';
    bookingState.photos.forEach((src, idx) => {
      const item = document.createElement('div');
      item.className = 'photo-preview-item';
      item.innerHTML = `
        <img src="${src}" alt="Foto Kerusakan ${idx + 1}">
        <button type="button" class="photo-delete-btn" data-index="${idx}" title="Hapus Foto">
          <i class="bi bi-x"></i>
        </button>
      `;
      item.querySelector('.photo-delete-btn').addEventListener('click', function(e) {
        e.stopPropagation();
        bookingState.photos.splice(idx, 1);
        renderPhotoPreviews();
      });
      grid.appendChild(item);
    });

    if (countIndicator) {
      countIndicator.textContent = `${bookingState.photos.length}/5 Foto Diunggah`;
    }
  }

  // Leaflet Map for Step 2
  let bookingMap = null;
  let bookingMarker = null;

  function initStep2Map() {
    const mapElement = document.getElementById('booking-leaflet-map');
    if (!mapElement || bookingMap) return;

    if (typeof L === 'undefined') {
      console.warn('Leaflet library is not loaded');
      return;
    }

    bookingMap = L.map('booking-leaflet-map').setView([bookingState.lat, bookingState.lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(bookingMap);

    const customIcon = L.divIcon({
      className: 'custom-map-pin',
      html: '<div style="background-color:#2563EB; width:34px; height:34px; border-radius:50%; border:3px solid #FFFFFF; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 4px 10px rgba(0,0,0,0.3);"><i class="bi bi-geo-alt-fill fs-5"></i></div>',
      iconSize: [34, 34],
      iconAnchor: [17, 34]
    });

    bookingMarker = L.marker([bookingState.lat, bookingState.lng], {
      draggable: true,
      icon: customIcon
    }).addTo(bookingMap);

    bookingMarker.on('dragend', function(e) {
      const position = bookingMarker.getLatLng();
      bookingState.lat = position.lat;
      bookingState.lng = position.lng;
      BetulinAja.showToast('Lokasi Diperbarui', `Pin disesuaikan ke koordinat: ${position.lat.toFixed(4)}, ${position.lng.toFixed(4)}`, 'info');
    });

    bookingMap.on('click', function(e) {
      bookingMarker.setLatLng(e.latlng);
      bookingState.lat = e.latlng.lat;
      bookingState.lng = e.latlng.lng;
    });

    setTimeout(() => {
      bookingMap.invalidateSize();
    }, 300);
  }

  // Geolocation button
  if (useLocationBtn) {
    useLocationBtn.addEventListener('click', function() {
      if (navigator.geolocation) {
        useLocationBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mencari Lokasi...';
        navigator.geolocation.getCurrentPosition(
          (position) => {
            bookingState.lat = position.coords.latitude;
            bookingState.lng = position.coords.longitude;
            if (bookingMap && bookingMarker) {
              bookingMap.setView([bookingState.lat, bookingState.lng], 16);
              bookingMarker.setLatLng([bookingState.lat, bookingState.lng]);
            }
            useLocationBtn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Gunakan Lokasi Saat Ini';
            BetulinAja.showToast('Lokasi Terdeteksi', 'Lokasi GPS Anda berhasil disematkan ke peta.', 'success');
          },
          (error) => {
            useLocationBtn.innerHTML = '<i class="bi bi-crosshair me-2"></i>Gunakan Lokasi Saat Ini';
            // Default demo coordinate
            BetulinAja.showToast('Simulasi GPS', 'Lokasi GPS disimulasikan di Kebayoran Baru, Jakarta Selatan.', 'info');
          }
        );
      }
    });
  }

  // Wizard Navigation
  if (nextBtn) {
    nextBtn.addEventListener('click', function() {
      if (validateCurrentStep()) {
        if (bookingState.currentStep < 5) {
          bookingState.currentStep++;
          renderStepUI();
        } else {
          // Final Submit
          submitBooking();
        }
      }
    });
  }

  if (prevBtn) {
    prevBtn.addEventListener('click', function() {
      if (bookingState.currentStep > 1) {
        bookingState.currentStep--;
        renderStepUI();
      }
    });
  }

  function validateCurrentStep() {
    if (bookingState.currentStep === 1) {
      const descInput = document.getElementById('problem-description');
      if (descInput && !descInput.value.trim()) {
        BetulinAja.showToast('Form Belum Lengkap', 'Silakan jelaskan kendala atau kerusakan yang dialami.', 'warning');
        descInput.focus();
        return false;
      }
      bookingState.description = descInput.value.trim();
      return true;
    }

    if (bookingState.currentStep === 2) {
      const addrInput = document.getElementById('address-input');
      if (addrInput && !addrInput.value.trim()) {
        BetulinAja.showToast('Form Belum Lengkap', 'Silakan masukkan alamat lengkap tempat perbaikan.', 'warning');
        addrInput.focus();
        return false;
      }
      bookingState.address = addrInput.value.trim();
      const notesInput = document.getElementById('address-notes');
      if (notesInput) bookingState.notes = notesInput.value.trim();
      return true;
    }

    return true;
  }

  function renderStepUI() {
    // Hide all steps, show current
    document.querySelectorAll('.wizard-step-pane').forEach((pane, idx) => {
      pane.classList.toggle('d-none', (idx + 1) !== bookingState.currentStep);
    });

    // Update Progress Pills
    document.querySelectorAll('.booking-step-item').forEach((item, idx) => {
      const stepNum = idx + 1;
      item.classList.remove('active', 'completed');
      if (stepNum < bookingState.currentStep) item.classList.add('completed');
      else if (stepNum === bookingState.currentStep) item.classList.add('active');
    });

    // Update Mobile Step Indicator
    const mobileCur = document.getElementById('mobile-current-step');
    const mobileTitle = document.getElementById('mobile-step-title');
    const stepTitles = [
      'Detail Kerusakan',
      'Lokasi & Jadwal',
      'Estimasi Biaya',
      'Pilih Teknisi',
      'Konfirmasi Pesanan'
    ];
    if (mobileCur) mobileCur.textContent = `Langkah ${bookingState.currentStep} dari 5`;
    if (mobileTitle) mobileTitle.textContent = stepTitles[bookingState.currentStep - 1];

    // Buttons
    if (prevBtn) prevBtn.classList.toggle('d-none', bookingState.currentStep === 1);
    if (nextBtn) {
      if (bookingState.currentStep === 5) {
        nextBtn.innerHTML = '<i class="bi bi-check2-circle me-2"></i>Konfirmasi & Buat Pesanan';
        nextBtn.classList.remove('btn-primary-custom');
        nextBtn.classList.add('btn-accent-green');
      } else {
        nextBtn.innerHTML = 'Lanjut <i class="bi bi-arrow-right ms-2"></i>';
        nextBtn.classList.add('btn-primary-custom');
        nextBtn.classList.remove('btn-accent-green');
      }
    }

    // Step-specific initializers
    if (bookingState.currentStep === 2) {
      setTimeout(initStep2Map, 150);
    } else if (bookingState.currentStep === 3) {
      updateCostCalculations();
    } else if (bookingState.currentStep === 5) {
      populateConfirmationView();
    }

    window.scrollTo({ top: 120, behavior: 'smooth' });
  }

  function updateCostCalculations() {
    const baseEl = document.getElementById('calc-base-price');
    const distanceEl = document.getElementById('calc-distance');
    const travelEl = document.getElementById('calc-travel-fee');
    const diffEl = document.getElementById('calc-difficulty-fee');
    const totalEl = document.getElementById('calc-total-estimate');

    bookingState.travelFee = bookingState.distanceKm * 5000;
    bookingState.totalEstimate = bookingState.basePrice + bookingState.travelFee + bookingState.difficultyFee;

    if (baseEl) baseEl.textContent = BetulinAja.formatRupiah(bookingState.basePrice);
    if (distanceEl) distanceEl.textContent = `${bookingState.distanceKm} km`;
    if (travelEl) travelEl.textContent = BetulinAja.formatRupiah(bookingState.travelFee);
    if (diffEl) diffEl.textContent = BetulinAja.formatRupiah(bookingState.difficultyFee);
    if (totalEl) totalEl.textContent = BetulinAja.formatRupiah(bookingState.totalEstimate);
  }

  // Step 4: Technician Selection
  document.querySelectorAll('.technician-selection-card').forEach(card => {
    card.addEventListener('click', function() {
      document.querySelectorAll('.technician-selection-card').forEach(c => c.classList.remove('selected'));
      this.classList.add('selected');
      const techId = this.dataset.id;
      if (techId === 'auto') {
        bookingState.technicianMode = 'auto';
        bookingState.selectedTechnician = {
          name: 'Sistem Otomatis (Rekomendasi Terbaik)',
          specialty: 'Dicarikan Teknisi Terdekat & Berating Tinggi',
          rating: 4.9,
          eta: '15 - 30 Menit'
        };
      } else {
        bookingState.technicianMode = 'manual';
        bookingState.selectedTechnician = {
          id: techId,
          name: this.dataset.name,
          rating: this.dataset.rating,
          specialty: this.dataset.specialty,
          eta: this.dataset.eta
        };
      }
      BetulinAja.showToast('Teknisi Dipilih', `Pilihan: ${bookingState.selectedTechnician.name}`, 'info');
    });
  });

  // Step 5: Confirmation View
  function populateConfirmationView() {
    const setVal = (id, val) => {
      const el = document.getElementById(id);
      if (el) el.textContent = val;
    };

    setVal('summary-service', `${bookingState.category} - ${bookingState.serviceType}`);
    setVal('summary-description', bookingState.description || 'Tidak ada deskripsi tambahan');
    setVal('summary-address', bookingState.address);
    setVal('summary-technician', bookingState.selectedTechnician.name);
    setVal('summary-total', BetulinAja.formatRupiah(bookingState.totalEstimate));

    const photosSummary = document.getElementById('summary-photos-list');
    if (photosSummary) {
      photosSummary.innerHTML = '';
      if (bookingState.photos.length === 0) {
        photosSummary.innerHTML = '<span class="text-secondary small fst-italic">Tidak ada foto diunggah</span>';
      } else {
        bookingState.photos.forEach(src => {
          const img = document.createElement('img');
          img.src = src;
          img.style.width = '55px';
          img.style.height = '55px';
          img.style.borderRadius = '8px';
          img.style.objectFit = 'cover';
          img.style.border = '1px solid #E2E8F0';
          photosSummary.appendChild(img);
        });
      }
    }
  }

  function submitBooking() {
    const orderNumber = 'ORD-' + Math.floor(100000 + Math.random() * 900000);
    const orderData = {
      orderNumber: orderNumber,
      category: bookingState.category,
      serviceType: bookingState.serviceType,
      total: bookingState.totalEstimate,
      technician: bookingState.selectedTechnician.name,
      address: bookingState.address,
      createdAt: new Date().toISOString(),
      status: 'Teknisi Dalam Perjalanan'
    };
    try {
      localStorage.setItem('active_demo_order', JSON.stringify(orderData));
    } catch(e) {}

    BetulinAja.showToast('Pesanan Berhasil Dibuat!', `Nomor Pesanan: ${orderNumber}. Mengarahkan ke halaman tracking...`, 'success', 2500);

    setTimeout(() => {
      window.location.href = '/customer/tracking?order=' + orderNumber;
    }, 1500);
  }
}

/**
 * -------------------------------------------------------------
 * 2. Order Tracking & Simulated Live Map
 * -------------------------------------------------------------
 */
function initTrackingPage() {
  const mapElement = document.getElementById('tracking-leaflet-map');
  if (!mapElement) return;

  if (typeof L === 'undefined') return;

  const customerLat = -6.2297;
  const customerLng = 106.8164;
  let techLat = -6.2420;
  let techLng = 106.8020;

  const trackingMap = L.map('tracking-leaflet-map').setView([customerLat, customerLng], 14);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(trackingMap);

  // Customer Pin
  const customerPin = L.divIcon({
    className: 'customer-pin',
    html: '<div style="background-color:#1E293B; width:34px; height:34px; border-radius:50%; border:3px solid #FFF; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 4px 10px rgba(0,0,0,0.3);"><i class="bi bi-house-door-fill"></i></div>',
    iconSize: [34, 34],
    iconAnchor: [17, 34]
  });
  L.marker([customerLat, customerLng], { icon: customerPin }).addTo(trackingMap).bindPopup('<b>Lokasi Anda</b><br>Jl. Sudirman No. 45');

  // Technician Vehicle Pin
  const techPin = L.divIcon({
    className: 'tech-pin',
    html: '<div style="background-color:#2563EB; width:38px; height:38px; border-radius:50%; border:3px solid #FFF; display:flex; align-items:center; justify-content:center; color:#fff; box-shadow:0 4px 12px rgba(37,99,235,0.4);"><i class="bi bi-bicycle fs-5"></i></div>',
    iconSize: [38, 38],
    iconAnchor: [19, 38]
  });
  const techMarker = L.marker([techLat, techLng], { icon: techPin }).addTo(trackingMap).bindPopup('<b>Budi Santoso (Teknisi)</b><br>Dalam Perjalanan');

  // Route Line
  const routeLine = L.polyline([
    [techLat, techLng],
    [-6.2360, 106.8080],
    [-6.2320, 106.8120],
    [customerLat, customerLng]
  ], {
    color: '#2563EB',
    weight: 4,
    opacity: 0.8,
    dashArray: '8, 8'
  }).addTo(trackingMap);

  trackingMap.fitBounds(routeLine.getBounds(), { padding: [50, 50] });

  // Status Simulation Stepper Button
  const btnNextStatus = document.getElementById('btn-simulate-status');
  if (btnNextStatus) {
    const statuses = [
      { name: 'Menunggu Konfirmasi', desc: 'Pesanan sedang diverifikasi sistem' },
      { name: 'Mencari Teknisi', desc: 'Mencari teknisi terdekat dari lokasi Anda' },
      { name: 'Teknisi Ditemukan', desc: 'Budi Santoso telah menerima pesanan' },
      { name: 'Dalam Perjalanan', desc: 'Teknisi sedang menuju lokasi (Est. 12 menit)' },
      { name: 'Sedang Dikerjakan', desc: 'Teknisi sedang memperbaiki AC di lokasi' },
      { name: 'Selesai', desc: 'Pekerjaan telah rampung dan terverifikasi' }
    ];
    let currentIdx = 3; // currently Dalam Perjalanan

    btnNextStatus.addEventListener('click', function() {
      currentIdx = (currentIdx + 1) % statuses.length;
      const statusObj = statuses[currentIdx];
      
      const badge = document.getElementById('tracking-status-badge');
      const desc = document.getElementById('tracking-status-desc');
      if (badge) badge.textContent = statusObj.name;
      if (desc) desc.textContent = statusObj.desc;

      // Update timeline UI
      document.querySelectorAll('.timeline-item').forEach((item, idx) => {
        item.classList.remove('completed', 'current');
        if (idx < currentIdx) item.classList.add('completed');
        else if (idx === currentIdx) item.classList.add('current');
      });

      BetulinAja.showToast('Status Pesanan Diperbarui', `Status saat ini: ${statusObj.name}`, 'info');
    });
  }
}

/**
 * -------------------------------------------------------------
 * 3. Interactive Chat & Call Demo
 * -------------------------------------------------------------
 */
function initChatDemo() {
  const sendBtn = document.getElementById('btn-send-chat');
  const chatInput = document.getElementById('chat-input-message');
  const chatContainer = document.getElementById('chat-messages-container');

  if (sendBtn && chatInput && chatContainer) {
    const sendMessage = () => {
      const text = chatInput.value.trim();
      if (!text) return;

      // Append customer message
      const custBubble = document.createElement('div');
      custBubble.className = 'chat-bubble customer';
      custBubble.innerHTML = `<div>${escapeHtml(text)}</div><span class="d-block text-white-50 text-end" style="font-size:0.7rem;">${getCurrentTime()}</span>`;
      chatContainer.appendChild(custBubble);
      chatInput.value = '';
      chatContainer.scrollTop = chatContainer.scrollHeight;

      // Simulate technician auto-reply after 1.2s
      setTimeout(() => {
        const replies = [
          'Baik Pak/Bu, saya sudah dekat dengan lokasi. Mohon ditunggu ya!',
          'Siap, peralatan tangga dan freon sudah saya bawa lengkap.',
          'Terima kasih konfirmasinya, sebentar lagi saya tiba di depan pagar.',
          'Oke dicatat, nanti saya cek dahulu kondisi indoor dan outdoor-nya.'
        ];
        const randomReply = replies[Math.floor(Math.random() * replies.length)];
        const techBubble = document.createElement('div');
        techBubble.className = 'chat-bubble technician';
        techBubble.innerHTML = `<div>${randomReply}</div><span class="d-block text-muted" style="font-size:0.7rem;">${getCurrentTime()}</span>`;
        chatContainer.appendChild(techBubble);
        chatContainer.scrollTop = chatContainer.scrollHeight;
        BetulinAja.showToast('Pesan Baru dari Teknisi', randomReply, 'info');
      }, 1200);
    };

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', (e) => {
      if (e.key === 'Enter') sendMessage();
    });
  }

  // Simulated Phone Call Demo
  const btnStartCall = document.getElementById('btn-start-call');
  const callStatusText = document.getElementById('call-status-text');
  let callInterval = null;

  if (btnStartCall && callStatusText) {
    btnStartCall.addEventListener('click', function() {
      callStatusText.textContent = 'Memanggil Teknisi...';
      let seconds = 0;
      setTimeout(() => {
        callStatusText.textContent = 'Terhubung — 00:01';
        clearInterval(callInterval);
        callInterval = setInterval(() => {
          seconds++;
          const mins = String(Math.floor(seconds / 60)).padStart(2, '0');
          const secs = String(seconds % 60).padStart(2, '0');
          callStatusText.textContent = `Terhubung — ${mins}:${secs}`;
        }, 1000);
      }, 2000);
    });

    const callModal = document.getElementById('phoneCallModal');
    if (callModal) {
      callModal.addEventListener('hidden.bs.modal', function() {
        clearInterval(callInterval);
        callStatusText.textContent = 'Memanggil...';
      });
    }
  }
}

function getCurrentTime() {
  const now = new Date();
  return `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
}

function escapeHtml(text) {
  const div = document.createElement('div');
  div.textContent = text;
  return div.innerHTML;
}
