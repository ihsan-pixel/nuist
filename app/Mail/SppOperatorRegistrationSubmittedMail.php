<?php

namespace App\Mail;

use App\Models\SppOperatorRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SppOperatorRegistrationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public SppOperatorRegistration $registration;

    public function __construct(SppOperatorRegistration $registration)
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Operator SPP Berhasil Dikirim',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spp-operator-registration-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
