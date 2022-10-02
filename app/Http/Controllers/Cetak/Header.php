<?php

namespace App\Http\Controllers\Cetak;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class Header extends BaseController
{
    public function KopSurat(PDFBarcode $pdf)
    {
        $border = 0;
        $heightCell = 2;
        $widthCell = 57;
        $fontSize = 9;
        $fontWeight = '';

        $pdf->SetFont('arial', $fontWeight, $fontSize);

        $pdf->setY(5);
        $pdf->Cell($widthCell+20, $heightCell+2, 'DENKESYAH 030401 BOGOR', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'PEMERIKSAAN KESEHATAN', $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'RUMAH SAKIT TK III 030702 SALAK', $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'JL JENDERAL SUDIRMAN NO 8 - BOGOR', $border);

        return $pdf;
    }

}
