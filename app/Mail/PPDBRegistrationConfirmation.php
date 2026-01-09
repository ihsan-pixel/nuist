<?php

namespace App\Mail;

use App\Models\PPDBPendaftar;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PPDBRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftar;

    /**
     * Create a new message instance.
     */
    public function __construct(PPDBPendaftar $pendaftar)
    {
        $this->pendaftar = $pendaftar;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Pendaftaran PPDB - ' . $this->pendaftar->ppdbSetting->nama_sekolah,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ppdb-registration-confirmation',
            with: [
                'pendaftar' => $this->pendaftar,
                'sekolah' => $this->pendaftar->ppdbSetting->sekolah,
                'ppdbSetting' => $this->pendaftar->ppdbSetting,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
