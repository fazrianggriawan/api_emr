<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\PDFBarcode;
use DateTime;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class Registrasi extends BaseController
{
    public function DataRegistrasi($noreg)
    {
        $data = DB::table('registrasi')
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien')
                ->where('noreg', $noreg)->get();
        if( count($data) > 0 ){
            $this->doPrint($data[0]);
        }
    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

        $tglLahir = DateTime::createFromFormat('Y-m-d', $data->tgl_lahir);
        $tanggal = DateTime::createFromFormat('Y-m-d', $data->tglReg);
        $dateCreated = DateTime::createFromFormat('Y-m-d H:i:s', $data->dateCreated);

        if(strtoupper($data->jns_kelamin) == 'L'){ $jnsKelamin = 'LAKI-LAKI'; }
        if(strtoupper($data->jns_kelamin) == 'P'){ $jnsKelamin = 'PEREMPUAN'; }

		$border = 0;
		$heightCell = 5;
		$widthCell = 60;
		$fontWeight = '';

		$fontSize = 9;
		$marginLeft = 3;
		$fontWeight = '';

		$pdf = new PDFBarcode();

		$pdf->AddPage('P', 'A4', 0);

        $pdf->Image('http://klinikeksekutif.com/demo/assets/tpl/images/logo.png', 10, 10, 43);

        $header = new HeaderPrint();
        $pdf = $header->GetHeader($pdf);

        $pdf->SetFont('arial', 'b', $fontSize+2);
        $pdf->ln(10);
        $pdf->Cell($widthCell+34, $heightCell+5, 'DATA REGISTRASI', $border);
        $pdf->Cell($widthCell-20, $heightCell+5, 'Antrian : '.substr($data->noreg, -3), 1, 0, 'C');
        $pdf->SetFont('arial', $fontWeight, $fontSize);
        $pdf->ln(12);
        $pdf->Cell($widthCell-30, $heightCell, 'No. Registrasi', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->noreg), $border);

        $pdf->Cell($widthCell-30, $heightCell, 'Tanggal', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell+4, $heightCell, strtoupper($tanggal->format('d-m-Y')), $border);

        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'No. Rekam Medis', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->norm), $border);

        $pdf->Cell($widthCell-30, $heightCell, 'Golongan Pasien', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell+4, $heightCell, strtoupper($data->group_pasien.' - '.$data->gol_pasien), $border);

        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Nama', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, substr(strtoupper($data->nama), 0, 40), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Jenis Kelamin', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($jnsKelamin), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Tanggal Lahir', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($tglLahir->format('d-m-Y')), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Alamat', $border);
        $pdf->Cell($widthCell-57, $heightCell, ':', $border);
        $pdf->Cell($widthCell+97, $heightCell, strtoupper($data->alamat), $border);

        $pdf->SetLineWidth(0.1);

        $pdf->ln(7);
        $pdf->SetFont('arial', 'b', $fontSize);
        $pdf->MultiCell($widthCell+130, $heightCell+2, 'Rincian Pemeriksaan', $border);
        $pdf->MultiCell($widthCell+130, 0, '', 1);
        $pdf->ln(1);
        $pdf->SetFont('arial', $fontWeight, $fontSize-1);
        $pdf->MultiCell($widthCell+4, $heightCell, '1.   '.strtoupper('paket mcu basic'), $border);

        $pdf->Cell($widthCell+30, $heightCell, '', $border);
        $pdf->Cell($widthCell+35, $heightCell, 'Petugas: '.strtoupper($data->userCreated).',  Tanggal: '.strtoupper($dateCreated->format('d-m-Y')).', Jam: '.$dateCreated->format('H:i:s'), $border, '', 'R');
		$pdf->Output();
        exit;
    }
}
