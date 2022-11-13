<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\Pasien;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class KartuPasien extends BaseController
{
    public function GoPrint($idPasien)
    {
        try {
            //code...
            $data = Pasien::where('id', $idPasien)->first();
            if( $data ){
                $this->doPrint($data);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }

    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

		$border = 0;
		$heightCell = 2;
		$widthCell = 50;

		$pdf = new PDFBarcode();
        $pdf->SetAutoPageBreak(false);
        $pdf->AddPage('P', [110,110], 0);
        $pdf->SetXY(13, 23);
        $pdf->SetMargins(13,0);

        $header = new HeaderPrint();
        $setting = $header->GetSetting( new stdClass() );
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

        $pdf->Cell($widthCell-27, $heightCell+2, 'No. RM', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize+2);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper($data->norm), $border);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln();
        $pdf->Cell($widthCell-27, $heightCell+2, 'Nama Pasien', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->Cell($widthCell, $heightCell+2, substr(strtoupper($data->nama),0,24), $border);
		$pdf->ln();
        $pdf->Cell($widthCell-27, $heightCell+2, 'Tgl. Lahir', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper(LibApp::dateHuman($data->tgl_lahir)), $border);
		$pdf->ln();
        $pdf->Cell($widthCell-27, $heightCell+2, 'Alamat', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->MultiCell($widthCell, $heightCell+2, strtoupper($data->alamat), $border, 'L');

        $pdf->Cell($widthCell-27, $heightCell+2, 'No. Telp', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper($data->tlp), $border);
		$pdf->ln();
        $pdf->Cell($widthCell-27, $heightCell+2, 'Penjamin', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper($data->nama), $border);
		$pdf->ln();
        $pdf->Cell($widthCell-27, $heightCell+2, 'NRP / NIP', $border);
        $pdf->Cell(3, $heightCell+2, ':', $border);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper($data->nrp_nip), $border);
		$pdf->ln();

		$pdf->Output();
        exit;
    }
}
