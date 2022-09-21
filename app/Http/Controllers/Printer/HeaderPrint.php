<?php

namespace App\Http\Controllers\Printer;

use DateTime;
use Laravel\Lumen\Routing\Controller as BaseController;


class HeaderPrint extends BaseController
{
    public function GetHeader($pdf)
    {
        $fontSize = 9;
        $border = 0;
        $fontWeight = '';
        $heightCell = 5;
		$widthCell = 57;

        $pdf->SetFont('arial', 'b', $fontSize+4);
        $pdf->Cell($widthCell-7);
        $pdf->Cell($widthCell+76, $heightCell+2, 'KLINIK EKSEKUTIF JAKARTA', $border);
        $pdf->SetFont('arial', $fontWeight, $fontSize);
        $pdf->ln();
        $pdf->Cell($widthCell-7);
        $pdf->Cell($widthCell+76, $heightCell, 'Jl. Minangkabau Barat No.17B RT.6/RW.8 Ps. Manggis', $border);
        $pdf->ln();
        $pdf->Cell($widthCell-7);
        $pdf->Cell($widthCell+76, $heightCell, 'Kecamatan Setiabudi, Kuningan. DKI Jakarta 12970. Telp: 021 - 229 091 28', $border);
        $pdf->SetLineWidth(0.4);
        $pdf->Line(10, 30, 200, 30);

        return $pdf;
    }
}
