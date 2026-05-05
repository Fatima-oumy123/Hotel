<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\Reservation;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    /**
     * Génère ou récupère la facture d'une réservation
     */
    public function generateInvoice(Reservation $reservation): Invoice
    {
        // Si la facture existe déjà, on la retourne
        $existing = Invoice::where('reservation_id', $reservation->id)->first();
        if ($existing && $existing->pdf_path && Storage::exists('public/' . $existing->pdf_path)) {
            return $existing;
        }

        $year  = date('Y');
        $count = Invoice::whereYear('created_at', $year)->count() + 1;
        $invoiceNumber = 'FACT-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);

        $taxRate  = (float) config('hotel.tax_rate', 18);
        $stayTax  = (float) config('hotel.stay_tax_per_night', 1000);
        $nights   = $reservation->nights;
        $subtotal = $reservation->total_amount;
        $taxAmount    = ($subtotal - $reservation->discount) * ($taxRate / 100);
        $stayTaxTotal = $stayTax * $nights;
        $total        = $subtotal - $reservation->discount + $taxAmount + $stayTaxTotal;

        $invoice = Invoice::updateOrCreate(
            ['reservation_id' => $reservation->id],
            [
                'invoice_number' => $invoiceNumber,
                'subtotal'       => $subtotal,
                'tax_rate'       => $taxRate,
                'tax_amount'     => round($taxAmount, 2),
                'stay_tax'       => round($stayTaxTotal, 2),
                'total'          => round($total, 2),
                'status'         => 'issued',
                'issued_at'      => now(),
            ]
        );

        // Génération PDF
        try {
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return $invoice;
            }

            $reservation->load('room.roomType');
            $pdf  = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', compact('reservation', 'invoice'));
            $path = 'invoices/' . $invoice->invoice_number . '.pdf';
            Storage::put('public/' . $path, $pdf->output());
            $invoice->update(['pdf_path' => $path]);
        } catch (\Exception $e) {
            \Log::warning('PDF generation failed: ' . $e->getMessage());
        }

        return $invoice;
    }
}
