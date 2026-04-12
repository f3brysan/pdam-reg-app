<?php

namespace App\Mail;

use App\Models\PermohonanBiling;
use App\Models\PermohonanTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PemohonanBillingMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PermohonanTransaction $permohonan,
        public PermohonanBiling $billing
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Perumdam Lawu Tirta Magetan — Tagihan pembayaran permohonan Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemohonan-billing',
        );
    }
}
