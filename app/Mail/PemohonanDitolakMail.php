<?php

namespace App\Mail;

use App\Models\PermohonanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PemohonanDitolakMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PermohonanTransaction $permohonan
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perumdam Lawu Tirta Magetan — Permohonan Anda ditolak',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemohonan-ditolak',
        );
    }
}
