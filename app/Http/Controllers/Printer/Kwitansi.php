<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\PDFBarcode;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class Kwitansi extends BaseController
{

    public function GoPrint($noKwitansi)
    {
        $pdf = new PDFBarcode();

		$pdf->AddPage('P', 'A4', 0);

        $header = new HeaderPrint();
        $pdf = $header->GetHeader($pdf);
        $setting = $header->GetSetting( new stdClass() );

        $pdf->SetFont('arial', 'b', $setting->fontSize+2);
        $pdf->MultiCell($setting->widthCell+80, $setting->heightCell+5, 'KWITANSI', $setting->border, 'C');
        $pdf->ln(2);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell(16, $setting->heightCell, 'Tanggal :', $setting->border);
        $pdf->Cell($setting->widthCell+20, $setting->heightCell, '29 September 2022 ', $setting->border);

        $pdf->Cell(17, $setting->heightCell, 'No. Nota : ', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'RJ.2202.1', $setting->border);
        $pdf->ln(10);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Telah terima dari', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'FAZRI ANGGRIAWAN', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Sejumlah Uang', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Rp. 7,500,000', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Terbilang', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'TUJUH JUTA LIMA RATUS RIBU RUPIAH', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Untuk Pembayaran', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'PERAWATAN RAWAT JALAN', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Ruangan', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'POLIKLINIK BEDAH', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Atas Nama Pasien', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'FAZRI ANGGRIAWAN', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'No. RM', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, '818181', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Jenis Pembayaran', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'TUNAI (UMUM)', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Status Pembayaran', $setting->border);
        $pdf->Cell(5, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'LUNAS', $setting->border);
        $pdf->ln(10);
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, $setting->kota.', 29 September 2022', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'an. Kepala Rumah Sakit', $setting->border);
        $pdf->ln(7);
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Kasir', $setting->border);
        $pdf->ln(17);
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'HERI WENDI HARTONO', $setting->border);

		$pdf->Output();
        exit;
    }
}
