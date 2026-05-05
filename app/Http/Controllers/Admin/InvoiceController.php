<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceService $invoiceService) {}

    public function index(Request $request)
    {
        $invoices = Invoice::with('reservation')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('invoice_number', 'like', "%{$request->search}%"))
            ->when($request->month, fn($q) => $q->whereMonth('issued_at', $request->month))
            ->latest()
            ->paginate(20);

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('reservation.room.roomType');
        return view('invoices.show', compact('invoice'));
    }

    public function generate(int $reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $invoice = $this->invoiceService->generateInvoice($reservation);
        return redirect()->route('invoices.show', $invoice)->with('success', "Facture {$invoice->invoice_number} générée.");
    }

    public function download(Invoice $invoice)
    {
        $invoice->load('reservation.room.roomType');
        if ($invoice->pdf_path && \Storage::exists('public/' . $invoice->pdf_path)) {
            return response()->download(storage_path('app/public/' . $invoice->pdf_path));
        }
        // Regénérer si absent
        $invoice = $this->invoiceService->generateInvoice($invoice->reservation);
        return response()->download(storage_path('app/public/' . $invoice->pdf_path));
    }

    public function cancel(Invoice $invoice)
    {
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Facture annulée.');
    }
}
