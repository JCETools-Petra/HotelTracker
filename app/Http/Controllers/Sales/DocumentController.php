<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentController extends Controller
{
    /**
     * Menghasilkan file PDF untuk Quotation.
     */
    public function generateQuotation(Booking $booking)
    {
        $data = ['booking' => $booking];
        
        // Load view, lalu convert ke PDF
        $pdf = Pdf::loadView('sales.documents.quotation', $data);

        // Download PDF
        return $pdf->download('quotation-'.$booking->booking_number.'.pdf');
    }
    
    /**
     * Menghasilkan file PDF untuk Invoice.
     */
    public function generateInvoice(Booking $booking)
    {
        $data = ['booking' => $booking];

        $pdf = Pdf::loadView('sales.documents.invoice', $data);
        
        // Tampilkan PDF di browser, bisa juga pakai ->download()
        return $pdf->stream('invoice-'.$booking->booking_number.'.pdf');
    }

    /**
     * Menghasilkan file PDF untuk BEO.
     */
    public function generateBeo(Booking $booking)
    {
        // Pastikan BEO ada sebelum membuat PDF
        if (!$booking->functionSheet) {
            return redirect()->back()->with('error', 'Function Sheet untuk booking ini belum dibuat.');
        }

        $data = [
            'booking' => $booking,
            'beo' => $booking->functionSheet
        ];
        
        // Load view template PDF, lalu convert ke PDF
        $pdf = Pdf::loadView('sales.documents.beo-pdf', $data);

        // Download PDF dengan nama file yang sesuai
        return $pdf->download('BEO-'.$booking->functionSheet->beo_number.'.pdf');
    }
}