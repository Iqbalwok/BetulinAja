<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $userName;
    public string $type; // registration, password_reset

    public function __construct(string $otp, string $userName = 'Pengguna', string $type = 'registration')
    {
        $this->otp = $otp;
        $this->userName = $userName;
        $this->type = $type;
    }

    public function envelope(): Envelope
    {
        $subject = $this->type === 'password_reset'
            ? "[BetulinAja] Kode OTP Reset Kata Sandi: {$this->otp}"
            : "[BetulinAja] Kode OTP Verifikasi Akun: {$this->otp}";

        return new Envelope(
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtml(),
        );
    }

    private function buildHtml(): string
    {
        $title = $this->type === 'password_reset' ? 'Permintaan Reset Kata Sandi' : 'Verifikasi Akun Baru';
        $intro = $this->type === 'password_reset'
            ? 'Kami menerima permintaan untuk mengatur ulang kata sandi akun BetulinAja Anda. Gunakan kode verifikasi di bawah ini untuk melanjutkan:'
            : 'Terima kasih telah mendaftar di <strong>BetulinAja</strong>. Gunakan kode OTP 6-digit berikut untuk mengonfirmasi email dan mengaktifkan akun Anda:';

        $safeName = htmlspecialchars($this->userName, ENT_QUOTES, 'UTF-8');
        $safeOtp = htmlspecialchars($this->otp, ENT_QUOTES, 'UTF-8');

        return <<<HTML
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{$title}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #0B1220; color: #F8FAFC;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #0B1220; padding: 40px 15px;">
    <tr>
      <td align="center">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #111C2E; border: 1px solid #334155; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
          <!-- Header -->
          <tr>
            <td style="background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 100%); padding: 24px 30px; text-align: center;">
              <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #FFFFFF; letter-spacing: -0.5px;">
                Betulin<span style="color: #60A5FA;">Aja</span>
              </h1>
              <p style="margin: 4px 0 0; font-size: 13px; color: #DBEAFE;">Platform Pemesanan Jasa Teknisi Terpercaya</p>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td style="padding: 32px 30px;">
              <h2 style="margin: 0 0 12px; font-size: 20px; font-weight: 700; color: #F8FAFC;">Halo, {$safeName}!</h2>
              <p style="margin: 0 0 24px; font-size: 14px; line-height: 1.6; color: #CBD5E1;">
                {$intro}
              </p>

              <!-- OTP Code Box -->
              <div style="background-color: #1E293B; border: 2px dashed #3B82F6; border-radius: 12px; padding: 20px; text-align: center; margin: 24px 0;">
                <span style="display: block; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #94A3B8; margin-bottom: 8px;">KODE VERIFIKASI RESMI</span>
                <span style="display: inline-block; font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #60A5FA; font-family: monospace;">
                  {$safeOtp}
                </span>
                <span style="display: block; font-size: 12px; color: #F59E0B; margin-top: 8px;">
                  &#9201; Berlaku selama <strong>5 menit</strong>
                </span>
              </div>

              <!-- Security Notice -->
              <div style="background-color: #0B1220; border-left: 4px solid #EF4444; border-radius: 6px; padding: 12px 16px; margin: 24px 0;">
                <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #CBD5E1;">
                  <strong style="color: #F8FAFC;">PERINGATAN KEAMANAN:</strong> JANGAN PERNAH membagikan kode OTP ini kepada siapa pun, termasuk pihak yang mengaku sebagai staf BetulinAja.
                </p>
              </div>

              <p style="margin: 0; font-size: 13px; color: #94A3B8; line-height: 1.5;">
                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini. Akun Anda tetap aman.
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background-color: #0B1220; padding: 20px 30px; text-align: center; border-top: 1px solid #1E293B;">
              <p style="margin: 0; font-size: 12px; color: #64748B;">
                &copy; 2026 <strong>BetulinAja Inc.</strong> &bull; Karya Mahasiswa Universitas Insan Pembangunan Indonesia (UNIPI).
              </p>
              <p style="margin: 4px 0 0; font-size: 11px; color: #475569;">
                Email ini dikirim secara otomatis oleh sistem komputasi awan BetulinAja.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
HTML;
    }
}
