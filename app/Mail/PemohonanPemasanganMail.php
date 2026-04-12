<?php

namespace App\Mail;

use App\Models\PermohonanOfficer;
use App\Models\PermohonanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PemohonanPemasanganMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PermohonanTransaction $permohonan,
        public PermohonanOfficer $permohonanOfficer
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perumdam Lawu Tirta Magetan — Jadwal pemasangan meter air',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemohonan-pemasangan',
        );
    }
}
