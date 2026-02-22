<?php

namespace App\Mail;

use App\Models\Asesi;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AsesiRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Asesi $asesi;

    public function __construct(Asesi $asesi)
    {
        $this->asesi = $asesi;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Update Status Pendaftaran - LSP SMKN 1 Ciamis',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.asesi-rejected',
        );
    }
}
