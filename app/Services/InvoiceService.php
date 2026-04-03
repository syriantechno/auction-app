<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Invoice;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Generate an automated invoice for a completed auction.
     */
    public function generateForAuction(Auction $auction): Invoice
    {
        $highestBid = $auction->bids()->orderBy('amount', 'desc')->first();

        if (!$highestBid) {
            throw new \Exception("Cannot generate invoice for auction without bids.");
        }

        $amount = $highestBid->amount;
        $commissionRate = 0.05; // 5% Commission
        $taxRate = 0.15;        // 15% VAT
        
        $commissionAmount = $amount * $commissionRate;
        $taxAmount = ($amount + $commissionAmount) * $taxRate;
        $totalAmount = $amount + $commissionAmount + $taxAmount;

        $invoice = Invoice::create([
            'auction_id' => $auction->id,
            'user_id' => $highestBid->user_id,
            'amount' => $amount,
            'commission_amount' => $commissionAmount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'type' => 'vehicle_purchase',
            'status' => 'unpaid',
        ]);

        $this->generatePDF($invoice);

        return $invoice;
    }

    /**
     * Generate PDF for the invoice.
     */
    public function generatePDF(Invoice $invoice): string
    {
        $invoice->load(['user', 'auction.car']);

        $pdf = Pdf::loadView('reports.invoice', ['invoice' => $invoice]);
        
        $path = "invoices/invoice-{$invoice->id}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        $invoice->update(['pdf_path' => $path]);

        return $path;
    }

    /**
     * Automated Sending Logic (Configurable via Settings).
     */
    public function sendInvoice(Invoice $invoice): void
    {
        $user = $invoice->user;
        $message = "Your invoice for auction #{$invoice->auction_id} is ready. Total amount: \${$invoice->amount}.";

        app(NotificationService::class)->notify($user, $message, 'invoice_ready', [
            'pdf_url' => asset('storage/' . $invoice->pdf_path),
        ]);
    }
}
