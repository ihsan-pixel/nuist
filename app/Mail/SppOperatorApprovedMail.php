<?php

namespace App\Mail;

use App\Models\SppOperatorRegistration;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SppOperatorApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public SppOperatorRegistration $registration;
    public string $plainPassword;

    public function __construct(User $user, SppOperatorRegistration $registration, string $plainPassword)
    {
        $this->user = $user;
        $this->registration = $registration;
        $this->plainPassword = $plainPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Akun Operator SPP Disetujui',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.spp-operator-approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
