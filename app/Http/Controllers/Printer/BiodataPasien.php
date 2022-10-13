<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\PDFBarcode;
use App\Models\Pasien;
use DateTime;
use Laravel\Lumen\Routing\Controller as BaseController;


class BiodataPasien extends BaseController
{
    public function GoPrint($idPasien)
    {
        $data = Pasien::GetAllData()->where('id', $idPasien)->first();

        if( $data ){
            $this->doPrint($data);
        }
    }

    public function doPrint($data)
    {
        header("Content-type:application/pdf");

        $tglLahir = DateTime::createFromFormat('Y-m-d', $data->tgl_lahir);

        if(strtoupper($data->jns_kelamin) == 'L'){ $jnsKelamin = 'LAKI-LAKI'; }
        if(strtoupper($data->jns_kelamin) == 'P'){ $jnsKelamin = 'PEREMPUAN'; }

		$border = 0;
		$heightCell = 7;
		$widthCell = 52;
		$widthCellData = 75;
		$widthCellData2 = 58;
		$fontWeight = '';

        $fontHead = 12;
		$fontBody = 10;

		$pdf = new PDFBarcode();

		$pdf->AddPage('P', [180,250], 0);
		$pdf->SetAutoPageBreak(false);

		$pdf->SetLeftMargin(15);
		$pdf->SetTopMargin(0);

		$pdf->SetFont('arial', 'B', $fontHead);

		$pdf->Code128( 35, 5,$data->norm, 30, 10); // Barcode
		$pdf->SetFont('arial', 'B', 22);
		$pdf->setX(5);
		$pdf->setY(5);
		$pdf->Cell(60, $heightCell+5,'', $border);
		$pdf->Cell(92, $heightCell+5,$data->norm, $border);

		$pdf->Ln(20);
		$pdf->Cell(127, $heightCell+2,strtoupper($data->nama), $border);

		$pdf->Ln(15);
		$pdf->SetFont('arial', 'B', $fontHead);
		$pdf->Cell(127, 10,'KARTU IDENTITAS PASIEN', $border);

		$pdf->SetFont('arial', $fontWeight, $fontBody);
		$pdf->Ln(12);
		$pdf->Cell($widthCell, $heightCell,'TANGGAL INPUT', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->dateCreated, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NO REKMED', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->norm, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA PASIEN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.strtoupper($data->nama), $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'GOLONGAN PASIEN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.strtoupper($data->r_group_pasien->name.' - '.$data->r_golpas->name), $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'ALAMAT', $border);
		$pdf->Cell(2, $heightCell,':', $border);
		$pdf->MultiCell(73, $heightCell,strtoupper($data->alamat), $border, 'L');

		$pdf->Ln(0);
		$pdf->Cell($widthCell, $heightCell,'TANGGAL LAHIR', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->tgl_lahir, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'JENIS KELAMIN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_jns_kelamin->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'GOLONGAN DARAH', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->gol_darah, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'AGAMA', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_agama->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'PENDIDIKAN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_pendidikan->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'PEKERJAAN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_pekerjaan->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NO. KARTU BPJS /  ASURANSI', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->no_asuransi, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NIP / NRP', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->nrp_nip, $border);

        $pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NIK', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->nik, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NO. TELEPON', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.$data->tlp, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'STATUS NIKAH', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_status_nikah->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'PENANGGUNG JAWAB', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'PANGKAT', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->r_pangkat->name, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA KESATUAN', $border);
		$pdf->Cell($widthCellData, $heightCell,': '.@$data->kesatuan, $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'HUB. KELUARGA', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA ISTRI', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA SUAMI', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA AYAH', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA IBU', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'NAMA PERUSAHAAN', $border);
		$pdf->Cell($widthCellData, $heightCell,': ', $border);

		$pdf->Ln();
		$pdf->Cell($widthCell, $heightCell,'TANGGAL CETAK', $border);
		$pdf->Cell(2, $heightCell,':', $border);
		$pdf->MultiCell(73, $heightCell,date('d-m-Y H:i:s'), $border, 'L');

		$pdf->SetAutoPageBreak(true);
		$pdf->AcceptPageBreak();

		$pdf->Output();
        exit;
    }
}
