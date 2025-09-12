<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;

class PdfController extends Controller
{
    public function invoice(Sale $sale)
    {

    $pdf = Pdf::loadView('sales.invoice', compact('sale'));
    $customPaper = [0, 0, 226.77, 1000.00];
    $pdf->setPaper($customPaper, 'portrait');
    return $pdf->stream('invoice.pdf');
    }
}
