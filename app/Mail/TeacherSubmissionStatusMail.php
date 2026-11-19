<?php

namespace App\Mail;

use App\Models\TeacherSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TeacherSubmissionStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public TeacherSubmission $submission, public string $event)
    {
        $this->submission->loadMissing(['submitter', 'madrasah']);
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'submitted' => 'Pengajuan Tenaga Pendidik Diterima',
            'needs_revision' => 'Pengajuan Tenaga Pendidik Memerlukan Perbaikan',
            'approved' => 'Pengajuan Tenaga Pendidik Disetujui',
        ];
        return new Envelope(subject: ($subjects[$this->event] ?? 'Pembaruan Pengajuan Tenaga Pendidik').' - NUIST');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.teacher_submission_status');
    }

    public function attachments(): array { return []; }
}
