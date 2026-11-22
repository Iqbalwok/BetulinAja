/**
 * BetulinAja — Digital Invoice & Struk Resmi Generator
 * Generates print-ready official receipts and PDF export for completed orders.
 * Creators: Iqbal Adityajaya, Maulana Rizki, Sapriadi Sandy (UNIPI)
 */

window.InvoiceGenerator = {
  /**
   * Opens and renders the invoice modal for a specific order.
   * @param {number|string} orderId 
   */
  async open(orderId) {
    let order = null;

    // Show loading toast
    if (window.BetulinAja && typeof window.BetulinAja.showToast === 'function') {
      window.BetulinAja.showToast('Menyiapkan Struk', 'Mengambil data transaksi resmi dari cloud database...', 'info', 1500);
    }

    try {
      const res = await ApiClient.get(`/orders/${orderId}`);
      order = res.data?.data || res.data;
    } catch (err) {
      console.warn('Gagal mengambil data detail order via API, mencoba mencari di cache lokal...', err);
      // Fallback if detail API call fails
      if (window.currentOrder && window.currentOrder.id == orderId) {
        order = window.currentOrder;
      }
    }

    if (!order) {
      alert('Maaf, detail pesanan tidak ditemukan atau koneksi ke cloud terputus.');
      return;
    }

    this.renderModal(order);
  },

  /**
   * Builds the modal HTML and displays it via Bootstrap 5 Modal
   * @param {Object} order 
   */
  renderModal(order) {
    let modalEl = document.getElementById('invoice-render-modal');
    if (!modalEl) {
      modalEl = document.createElement('div');
      modalEl.id = 'invoice-render-modal';
      modalEl.className = 'modal fade';
      modalEl.tabIndex = -1;
      modalEl.setAttribute('aria-hidden', 'true');
      document.body.appendChild(modalEl);
    }

    const orderDate = ApiClient.formatDate(order.created_at);
    const invoiceNum = `INV-${order.order_number || ('BA' + String(order.id).padStart(6, '0'))}`;
    const totalAmount = Number(order.final_price || order.total_estimate || 0);
    const platformFee = 2000;
    const baseServicePrice = Math.max(0, totalAmount - platformFee);

    const partnerName = order.partner ? (order.partner.name || 'Mitra Resmi BetulinAja') : 'Teknisi Tersertifikasi';
    const partnerPhone = order.partner ? (order.partner.phone || '-') : '-';
    const customerName = order.customer ? (order.customer.name || 'Pelanggan BetulinAja') : (Auth.getUser()?.name || 'Pelanggan BetulinAja');
    const customerPhone = order.customer ? (order.customer.phone || '-') : (Auth.getUser()?.phone || '-');

    modalEl.innerHTML = `
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content shadow-lg border-0 rounded-4 overflow-hidden">
          <!-- Modal Header (Hidden on print) -->
          <div class="modal-header bg-light border-bottom py-3 no-print">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-receipt text-primary fs-5"></i>
              <h6 class="modal-title fw-bold mb-0">Bukti Transaksi & Struk Resmi</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
              <button type="button" class="btn btn-primary-custom btn-sm px-3 fw-semibold shadow-sm" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak / Simpan PDF
              </button>
              <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          </div>

          <!-- Modal Body: The Printable Invoice Content -->
          <div class="modal-body p-4 p-md-5 printable-invoice bg-white text-dark">
            <div class="invoice-card p-2 p-md-3">
              <!-- Top Header: Logo & Company -->
              <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 border-bottom pb-4 mb-4">
                <div>
                  <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="bg-primary text-white rounded-3 p-2 d-inline-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                      <i class="bi bi-tools fs-5"></i>
                    </div>
                    <h3 class="fw-bold mb-0 text-dark">Betulin<span class="text-primary">Aja</span></h3>
                  </div>
                  <p class="text-secondary small mb-1">Platform Pemesanan Jasa & Perbaikan Rumah Berbasis Cloud</p>
                  <p class="text-muted small mb-0" style="font-size: 0.75rem;">
                    Proyek Riset & Aplikasi Komputasi Awan &bull; Univ. Insan Pembangunan Indonesia (UNIPI)
                  </p>
                </div>
                <div class="text-md-end">
                  <div class="invoice-stamp mb-2">LUNAS / COMPLETED</div>
                  <div class="fw-bold text-dark fs-6">${invoiceNum}</div>
                  <div class="text-secondary small">Tanggal: ${orderDate}</div>
                </div>
              </div>

              <!-- Customer & Partner Info Grid -->
              <div class="row g-4 mb-4 pb-3 border-bottom">
                <div class="col-6">
                  <span class="text-uppercase text-muted fw-bold small d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Diterbitkan Untuk:</span>
                  <div class="fw-bold text-dark fs-6 mb-1">${customerName}</div>
                  <div class="text-secondary small mb-1"><i class="bi bi-telephone me-1 text-muted"></i>${customerPhone}</div>
                  <div class="text-secondary small"><i class="bi bi-geo-alt me-1 text-muted"></i>${order.address_text || 'Alamat Terdaftar Pelanggan'}</div>
                </div>
                <div class="col-6 text-md-end">
                  <span class="text-uppercase text-muted fw-bold small d-block mb-1" style="font-size: 0.72rem; letter-spacing: 0.05em;">Mitra Pelaksana:</span>
                  <div class="fw-bold text-dark fs-6 mb-1">${partnerName}</div>
                  <div class="text-secondary small mb-1"><i class="bi bi-shield-check text-success me-1"></i>Teknisi Terverifikasi BetulinAja</div>
                  <div class="text-secondary small"><i class="bi bi-whatsapp text-success me-1"></i>${partnerPhone}</div>
                </div>
              </div>

              <!-- Service Details Table -->
              <div class="table-responsive mb-4">
                <table class="table table-bordered align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th class="py-2 px-3 small text-uppercase text-secondary" style="font-size: 0.75rem;">Deskripsi Layanan</th>
                      <th class="py-2 px-3 small text-uppercase text-secondary text-center" style="width: 120px; font-size: 0.75rem;">Garansi</th>
                      <th class="py-2 px-3 small text-uppercase text-secondary text-end" style="width: 160px; font-size: 0.75rem;">Jumlah</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td class="p-3">
                        <strong class="d-block text-dark">${order.service?.name || 'Jasa Perbaikan / Service Rumah'}</strong>
                        <span class="text-muted small">${order.complaint_details || 'Pengerjaan sesuai standar operasional BetulinAja.'}</span>
                        ${order.schedule_date ? `<div class="text-muted small mt-1"><i class="bi bi-calendar-event me-1"></i>Jadwal Kunjungan: ${order.schedule_date} (${order.schedule_time || 'Fleksibel'})</div>` : ''}
                      </td>
                      <td class="p-3 text-center">
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 small">
                          <i class="bi bi-check-circle me-1"></i>30 Hari
                        </span>
                      </td>
                      <td class="p-3 text-end fw-semibold text-dark">
                        ${ApiClient.formatRupiah(baseServicePrice)}
                      </td>
                    </tr>
                    <tr>
                      <td class="p-3" colspan="2">
                        <span class="text-dark small fw-medium">Biaya Pemeliharaan Sistem & Cloud Platform</span>
                        <div class="text-muted" style="font-size: 0.72rem;">Serverless API, real-time tracking, dan asuransi proteksi layanan.</div>
                      </td>
                      <td class="p-3 text-end fw-semibold text-dark">
                        ${ApiClient.formatRupiah(platformFee)}
                      </td>
                    </tr>
                  </tbody>
                  <tfoot class="table-light">
                    <tr>
                      <th colspan="2" class="p-3 text-end fw-bold text-dark fs-6">TOTAL PEMBAYARAN:</th>
                      <th class="p-3 text-end fw-bold text-primary fs-5">${ApiClient.formatRupiah(totalAmount)}</th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <!-- Security, Guarantee & Signature Badge -->
              <div class="p-3 rounded-3 bg-light border border-light-subtle mb-4">
                <div class="row align-items-center g-3">
                  <div class="col-md-8">
                    <div class="d-flex align-items-start gap-2">
                      <i class="bi bi-shield-lock-fill text-success fs-4 flex-shrink-0"></i>
                      <div>
                        <div class="fw-bold text-dark small">Jaminan Garansi 30 Hari & Keamanan Terverifikasi</div>
                        <p class="text-secondary mb-0" style="font-size: 0.75rem; line-height: 1.4;">
                          Jika dalam kurun waktu 30 hari keluhan yang sama muncul kembali, teknisi kami akan melakukan perbaikan ulang tanpa biaya jasa tambahan.
                        </p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 text-md-end">
                    <span class="badge bg-primary text-white p-2 small">
                      <i class="bi bi-cloud-check-fill me-1"></i> Verifikasi Cloud Aktif
                    </span>
                  </div>
                </div>
              </div>

              <!-- Footer Statement -->
              <div class="pt-3 border-top text-center text-secondary" style="font-size: 0.75rem;">
                <p class="mb-1">Terima kasih telah mempercayakan perbaikan rumah Anda kepada <strong>BetulinAja</strong>.</p>
                <p class="text-muted mb-0">
                  Tim Pengembang: <strong>Maulana Rizki</strong> (Penemu) &bull; <strong>Iqbal Adityajaya</strong> (Pembuat) &bull; <strong>Sapriadi Sandy</strong> (CEO) &mdash; Universitas Insan Pembangunan Indonesia (UNIPI).
                </p>
              </div>
            </div>
          </div>

          <!-- Modal Footer (Hidden on print) -->
          <div class="modal-footer bg-light border-top py-2 px-4 no-print justify-content-between">
            <div class="text-muted small" style="font-size: 0.75rem;">
              <i class="bi bi-info-circle me-1"></i>Tekan Cetak untuk print fisik atau simpan sebagai dokumen PDF.
            </div>
            <div class="d-flex gap-2">
              <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Tutup</button>
              <button type="button" class="btn btn-primary-custom btn-sm px-4 fw-semibold" onclick="window.print()">
                <i class="bi bi-printer me-1"></i> Cetak Struk
              </button>
            </div>
          </div>
        </div>
      </div>
    `;

    const bsModal = new bootstrap.Modal(modalEl);
    bsModal.show();
  }
};

// Global shorthand
window.printOrderInvoice = function(orderId) {
  InvoiceGenerator.open(orderId);
};
