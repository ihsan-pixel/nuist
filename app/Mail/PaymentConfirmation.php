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
        // TODO: Generate and attach PDF invoice
        // For now, return empty array
        return [];
    }
}
