<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\PDFBarcode;
use App\Models\Pasien;
use DateTime;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class Barcode extends BaseController
{
    public function GoPrint($idPasien)
    {
        $data = Pasien::where('id', $idPasien)->first();
        if( $data ){
            $this->doPrint($data);
        }
    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

        $tglLahir = DateTime::createFromFormat('Y-m-d', $data->tgl_lahir);

        if(strtoupper($data->jns_kelamin) == 'P'){ $jnsKelamin = 'LAKI-LAKI'; }
        if(strtoupper($data->jns_kelamin) == 'W'){ $jnsKelamin = 'PEREMPUAN'; }

		$border = 0;
		$heightCell = 3;
		$widthCell = 57;
		$fontWeight = '';

		$fontBody = 9;
		$marginLeft = 3;
		$fontWeight = '';

		$pdf = new PDFBarcode();

		$pdf->AddPage('L', [60,30], 0);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetLeftMargin($marginLeft);
		$pdf->SetTopMargin(0);

		$pdf->SetFont('arial', 'b', $fontBody);
		$pdf->SetY(1);
		$pdf->Cell($widthCell, $heightCell+2, substr(strtoupper($data->nama),0,24), $border);
        $pdf->SetFont('arial', $fontWeight, $fontBody);
		$pdf->ln();
        $heightCell++;
        $pdf->Cell($widthCell, $heightCell, $tglLahir->format('d-m-Y').', '.$jnsKelamin, $border);
		$pdf->ln();
		$pdf->Cell($widthCell, $heightCell, substr(strtoupper($data->alamat),0,24), $border);
		$pdf->SetFont('arial', '', $fontBody);
		$pdf->Code128( $marginLeft+1.3, 15, $data->norm, 40, 9); // Barcode
		$pdf->SetFont('arial', $fontWeight, $fontBody);
        $pdf->ln(15);
        $pdf->Cell($widthCell, $heightCell, 'No.RM : '.strtoupper($data->norm), $border);

		$pdf->Output();
        exit;
    }
}
