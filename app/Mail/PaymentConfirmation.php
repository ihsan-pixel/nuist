<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Payment;
use App\Models\Tagihan;
use App\Models\Madrasah;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;
    public $tagihan;
    public $madrasah;

    /**
     * Create a new message instance.
     */
    public function __construct(Payment $payment, Tagihan $tagihan, Madrasah $madrasah)
    {
        $this->payment = $payment;
        $this->tagihan = $tagihan;
        $this->madrasah = $madrasah;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pembayaran - ' . $this->tagihan->nomor_invoice,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-confirmation',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        try {
            // Generate PDF invoice
            $pdf = $this->generateInvoicePDF();
            if ($pdf) {
                return [
                    \Illuminate\Mail\Mailables\Attachment::fromData(fn () => $pdf, 'invoice-' . $this->tagihan->nomor_invoice . '.pdf')
                        ->withMime('application/pdf'),
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Failed to generate PDF attachment: ' . $e->getMessage());
        }

        return [];
    }

    private function generateInvoicePDF()
    {
        try {
            $tahun = $this->tagihan->tahun_anggaran;
            $madrasah = $this->madrasah;
            $dataSekolah = \App\Models\DataSekolah::where('madrasah_id', $madrasah->id)->where('tahun', $tahun)->first();

            if (!$dataSekolah) {
                return null;
            }

            $setting = \App\Models\UppmSetting::where('tahun_anggaran', $tahun)->where('aktif', true)->first();

            if (!$setting) {
                return null;
            }

            // Create school data object
            $schoolData = (object) [
                'jumlah_siswa' => $dataSekolah->jumlah_siswa ?? 0,
                'jumlah_pns_sertifikasi' => $dataSekolah->jumlah_pns_sertifikasi ?? 0,
                'jumlah_pns_non_sertifikasi' => $dataSekolah->jumlah_pns_non_sertifikasi ?? 0,
                'jumlah_gty_sertifikasi' => $dataSekolah->jumlah_gty_sertifikasi ?? 0,
                'jumlah_gty_sertifikasi_inpassing' => $dataSekolah->jumlah_gty_sertifikasi_inpassing ?? 0,
                'jumlah_gty_non_sertifikasi' => $dataSekolah->jumlah_gty_non_sertifikasi ?? 0,
                'jumlah_gtt' => $dataSekolah->jumlah_gtt ?? 0,
                'jumlah_pty' => $dataSekolah->jumlah_pty ?? 0,
                'jumlah_ptt' => $dataSekolah->jumlah_ptt ?? 0,
                'jumlah_karyawan_tetap' => 0,
                'jumlah_karyawan_tidak_tetap' => 0,
            ];

            // Calculate nominals
            $nominalBulanan = $this->hitungNominalBulanan($schoolData, $setting);
            $totalTahunan = $nominalBulanan * 12;

            $rincian = [
                'siswa' => ($schoolData->jumlah_siswa * $setting->nominal_siswa) * 12,
                'pns_sertifikasi' => ($schoolData->jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi) * 12,
                'pns_non_sertifikasi' => ($schoolData->jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi) * 12,
                'gty_sertifikasi' => ($schoolData->jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi) * 12,
                'gty_sertifikasi_inpassing' => ($schoolData->jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing) * 12,
                'gty_non_sertifikasi' => ($schoolData->jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi) * 12,
                'gtt' => ($schoolData->jumlah_gtt * $setting->nominal_gtt) * 12,
                'pty' => ($schoolData->jumlah_pty * $setting->nominal_pty) * 12,
                'ptt' => ($schoolData->jumlah_ptt * $setting->nominal_ptt) * 12,
            ];

            $yayasan = \App\Models\Yayasan::find(1);

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('uppm.invoice-pdf', compact('madrasah', 'dataSekolah', 'setting', 'nominalBulanan', 'totalTahunan', 'rincian', 'tahun', 'yayasan'));
            return $pdf->output();
        } catch (\Exception $e) {
            \Log::error('Error generating PDF: ' . $e->getMessage());
            return null;
        }
    }

    private function hitungNominalBulanan($schoolData, $setting)
    {
        $nominal = 0;

        $nominal += $schoolData->jumlah_siswa * $setting->nominal_siswa;
        $nominal += $schoolData->jumlah_pns_sertifikasi * $setting->nominal_pns_sertifikasi;
        $nominal += $schoolData->jumlah_pns_non_sertifikasi * $setting->nominal_pns_non_sertifikasi;
        $nominal += $schoolData->jumlah_gty_sertifikasi * $setting->nominal_gty_sertifikasi;
        $nominal += $schoolData->jumlah_gty_sertifikasi_inpassing * $setting->nominal_gty_sertifikasi_inpassing;
        $nominal += $schoolData->jumlah_gty_non_sertifikasi * $setting->nominal_gty_non_sertifikasi;
        $nominal += $schoolData->jumlah_gtt * $setting->nominal_gtt;
        $nominal += $schoolData->jumlah_pty * $setting->nominal_pty;
        $nominal += $schoolData->jumlah_ptt * $setting->nominal_ptt;
        $nominal += ($schoolData->jumlah_karyawan_tetap ?? 0) * ($setting->nominal_karyawan_tetap ?? 0);
        $nominal += ($schoolData->jumlah_karyawan_tidak_tetap ?? 0) * ($setting->nominal_karyawan_tidak_tetap ?? 0);

        return $nominal;
    }
}
