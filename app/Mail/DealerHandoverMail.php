<?php

namespace App\Mail;

use App\Models\StockEntry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DealerHandoverMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public StockEntry $stockEntry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Vehicle Ready for Handover — Ref: ' . $this->stockEntry->reference_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.dealer-handover',
            with: [
                'stockEntry'    => $this->stockEntry,
                'car'           => $this->stockEntry->car,
                'refCode'       => $this->stockEntry->reference_code,
                'purchasePrice' => number_format((float) $this->stockEntry->dealer_bid, 2),
            ],
        );
    }
}
