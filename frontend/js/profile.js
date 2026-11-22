/**
 * BetulinAja — Customer Profile Management Script
 * Loads current user data from Laravel API (/api/v1/auth/profile),
 * enables editing of personal details (Name, WhatsApp, Email, Primary Address, Password),
 * and saves updates directly to Railway MySQL Database.
 */

document.addEventListener('DOMContentLoaded', function() {
  if (!Auth.requireAuth()) return;
  loadUserProfile();
  setupProfileForm();
  setupPasswordForm();
});

async function loadUserProfile() {
  try {
    const res = await ApiClient.get('/auth/profile');
    const user = res.data?.data || res.data || Auth.getUser();

    if (!user) return;

    // Update displayed identity
    const nameDisplays = document.querySelectorAll('.user-name-display');
    nameDisplays.forEach(el => el.textContent = user.name || 'Pengguna');

    const emailDisplays = document.querySelectorAll('.user-email-display');
    emailDisplays.forEach(el => el.textContent = user.email || '-');

    const avatarDisplays = document.querySelectorAll('.user-avatar-display');
    const initials = (user.name || 'P').substring(0, 2).toUpperCase();
    avatarDisplays.forEach(el => {
      el.textContent = initials;
    });

    // Populate inputs
    const nameInput = document.getElementById('profile-name');
    if (nameInput) nameInput.value = user.name || '';

    const phoneInput = document.getElementById('profile-phone');
    if (phoneInput) phoneInput.value = user.phone || '';

    const emailInput = document.getElementById('profile-email');
    if (emailInput) emailInput.value = user.email || '';

    // Populate primary address
    const primaryAddress = (user.addresses && user.addresses.length > 0)
      ? user.addresses.find(a => a.is_primary) || user.addresses[0]
      : null;

    const addressInput = document.getElementById('profile-address');
    if (addressInput && primaryAddress) {
      addressInput.value = primaryAddress.address || '';
    }

    const cityInput = document.getElementById('profile-city');
    if (cityInput && primaryAddress) {
      cityInput.value = primaryAddress.city || '';
    }

    const districtInput = document.getElementById('profile-district');
    if (districtInput && primaryAddress) {
      districtInput.value = primaryAddress.district || '';
    }

    // Render address cards
    renderSavedAddresses(user.addresses || []);

  } catch (err) {
    console.error('Gagal memuat profil pengguna:', err);
    ApiClient.showToast('Gagal Memuat Profil', err.message || 'Tidak dapat mengambil data profil dari server.', 'danger');
  }
}

function renderSavedAddresses(addresses) {
  const container = document.getElementById('saved-addresses-container');
  if (!container) return;

  if (!addresses || addresses.length === 0) {
    container.innerHTML = `
      <div class="p-3 text-center bg-light rounded-3 border">
        <i class="bi bi-geo-alt text-secondary fs-4 d-block mb-1"></i>
        <p class="text-secondary small mb-0">Belum ada alamat tersimpan. Silakan isi alamat utama di formulir profil di atas.</p>
      </div>
    `;
    return;
  }

  container.innerHTML = addresses.map(addr => `
    <div class="border rounded-3 p-3 mb-2 ${addr.is_primary ? 'bg-light border-primary-subtle' : ''}">
      <div class="d-flex align-items-center justify-content-between mb-1">
        <div class="d-flex align-items-center gap-2">
          <h6 class="fw-bold text-dark mb-0">${addr.label || 'Alamat'}</h6>
          ${addr.is_primary ? '<span class="badge bg-primary-subtle text-primary small py-0.5 px-2">Utama</span>' : ''}
        </div>
      </div>
      <p class="text-secondary small mb-1">${addr.address}</p>
      <div class="text-muted small">
        <i class="bi bi-pin-map text-danger me-1"></i>${addr.district ? `${addr.district}, ` : ''}${addr.city || 'Jabodetabek'}
      </div>
    </div>
  `).join('');
}

function setupProfileForm() {
  const form = document.getElementById('profile-info-form');
  if (!form) return;

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerHTML : 'Simpan';

    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan ke Cloud...';
    }

    try {
      const payload = {
        name: document.getElementById('profile-name').value.trim(),
        phone: document.getElementById('profile-phone').value.trim(),
        email: document.getElementById('profile-email').value.trim(),
        address: document.getElementById('profile-address') ? document.getElementById('profile-address').value.trim() : undefined,
        city: document.getElementById('profile-city') ? document.getElementById('profile-city').value.trim() : undefined,
        district: document.getElementById('profile-district') ? document.getElementById('profile-district').value.trim() : undefined,
      };

      const res = await ApiClient.put('/auth/profile', payload);
      const updatedUser = res.data?.data || res.data;

      // Update cached user in localStorage
      if (updatedUser) {
        Auth.setUser(updatedUser);
      }

      ApiClient.showToast('Profil Berhasil Diperbarui', 'Data profil dan alamat Anda telah tersimpan secara permanen di database.', 'success', 3000);
      loadUserProfile();

    } catch (err) {
      ApiClient.showToast('Gagal Menyimpan Profil', err.message || 'Terjadi kesalahan saat menyimpan profil.', 'danger', 4000);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    }
  });
}

function setupPasswordForm() {
  const form = document.getElementById('profile-password-form');
  if (!form) return;

  form.addEventListener('submit', async function(e) {
    e.preventDefault();
    const currentPass = document.getElementById('profile-current-password').value;
    const newPass = document.getElementById('profile-new-password').value;
    const confirmPass = document.getElementById('profile-confirm-password').value;

    if (newPass.length < 8) {
      ApiClient.showToast('Password Kurang Panjang', 'Kata sandi baru minimal 8 karakter.', 'warning');
      return;
    }

    if (newPass !== confirmPass) {
      ApiClient.showToast('Konfirmasi Tidak Cocok', 'Kata sandi baru dan konfirmasi kata sandi tidak sama.', 'warning');
      return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn ? btn.innerHTML : 'Ubah Password';

    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';
    }

    try {
      await ApiClient.put('/auth/profile', {
        current_password: currentPass,
        new_password: newPass,
        new_password_confirmation: confirmPass,
      });

      ApiClient.showToast('Kata Sandi Diperbarui', 'Kata sandi akun Anda telah berhasil diubah.', 'success', 3500);
      form.reset();

    } catch (err) {
      ApiClient.showToast('Gagal Mengubah Kata Sandi', err.message || 'Password saat ini salah atau server mengalami kendala.', 'danger', 4000);
    } finally {
      if (btn) {
        btn.disabled = false;
        btn.innerHTML = originalText;
      }
    }
  });
}
