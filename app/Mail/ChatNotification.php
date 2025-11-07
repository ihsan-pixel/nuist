<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Chat;
use App\Models\User;

class ChatNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $chat;
    public $sender;

    /**
     * Create a new message instance.
     */
    public function __construct(Chat $chat, User $sender)
    {
        $this->chat = $chat;
        $this->sender = $sender;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pesan Baru dari ' . $this->sender->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.chat-notification',
            with: [
                'chat' => $this->chat,
                'sender' => $this->sender,
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
