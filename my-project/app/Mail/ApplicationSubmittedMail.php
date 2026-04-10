<?php

namespace App\Mail;

use App\Models\Candidature;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Candidature $candidature;

    public function __construct(Candidature $candidature)
    {
        $this->candidature = $candidature;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle candidature: ' . $this->candidature->offre->titre,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.application-submitted',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
