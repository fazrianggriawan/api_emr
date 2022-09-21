<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\PDFBarcode;
use DateTime;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class Pasien extends BaseController
{
    public function BiodataPasien($idPasien)
    {
        $data = DB::table('pasien')->where('id', $idPasien)->get();
        if( count($data) > 0 ){
            $this->doPrint($data[0]);
        }
    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

        $tglLahir = DateTime::createFromFormat('Y-m-d', $data->tgl_lahir);

        if(strtoupper($data->jns_kelamin) == 'L'){ $jnsKelamin = 'LAKI-LAKI'; }
        if(strtoupper($data->jns_kelamin) == 'P'){ $jnsKelamin = 'PEREMPUAN'; }

		$border = 0;
		$heightCell = 5;
		$widthCell = 57;
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
        $pdf->Cell($widthCell+100, $heightCell+5, 'DATA PASIEN', $border);
        $pdf->SetFont('arial', $fontWeight, $fontSize);
        $pdf->ln(10);
        $pdf->Cell($widthCell-30, $heightCell, 'No. Rekam Medis', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->norm), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Nama', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->nama), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Jenis Kelamin', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($jnsKelamin), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Tanggal Lahir', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($tglLahir->format('d-m-Y')), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'NIK', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->nik, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Alamat', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->alamat), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Kecamatan', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->kecamatan), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Kelurahan', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->kelurahan), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Kota', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->kota), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Provinsi', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, strtoupper($data->provinsi), $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Negara', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->negara, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Status Nikah', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->status_nikah, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Pekerjaan', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->pekerjaan, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Pendidikan', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->pendidikan, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Gol. Darah', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->gol_darah, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'No. Asuransi', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->no_asuransi, $border);
        $pdf->ln();
        $pdf->Cell($widthCell-30, $heightCell, 'Golongan Pasien', $border);
        $pdf->Cell($widthCell-53, $heightCell, ':', $border);
        $pdf->Cell($widthCell, $heightCell, $data->group_pasien.' - '.$data->gol_pasien, $border);

		$pdf->Output();
        exit;
    }
}
