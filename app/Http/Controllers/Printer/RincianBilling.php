<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\Billing;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class RincianBilling extends BaseController
{
    public function GoPrint($noreg)
    {
        $data = Billing::GetAllData()->where('noreg', $noreg)->get();
        $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();

        $pdf = new PDFBarcode();

		$pdf->AddPage('P', 'A4', 0);

        $header = new HeaderPrint();
        $pdf = $header->GetHeader($pdf);
        $setting = $header->GetSetting( new stdClass() );

        $pdf->SetFont('arial', 'b', $setting->fontSize+2);
        $pdf->Cell($setting->widthCell+45, $setting->heightCell, 'RINCIAN BIAYA PERAWATAN', $setting->border);
        $pdf->SetFont('arial', $setting->fontWeight, 8.5);
        $pdf->Cell($setting->widthCell-20, $setting->heightCell, 'NO. ANTRIAN', 'L');
        $pdf->ln();
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($setting->widthCell+45, $setting->heightCell, 'Tanggal Perawatan : '.LibApp::dateHuman($registrasi->tglReg).' s/d '.LibApp::dateHuman($registrasi->tglReg), $setting->border);

        if( isset($registrasi->registrasi_antrian->r_antrian->nomor) && $registrasi->id_jns_perawatan == 'rj' ){
            $separator = ($registrasi->registrasi_antrian->r_antrian->prefix == '') ? '' : '-' ;
            $pdf->SetFont('arial', 'b', 12);
            $pdf->Cell($setting->widthCell-20, $setting->heightCell, $registrasi->registrasi_antrian->r_antrian->prefix.$separator.$registrasi->registrasi_antrian->r_antrian->nomor, 'L');
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        }

        $pdf->Code128($setting->widthCell+103, 29, $registrasi->noreg, 40, 10); // Barcode
        $pdf->ln(4);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $setting->heightCell = $setting->heightCell - 1;
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'No. Reg / No. RM', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->noreg.' / '.$registrasi->pasien->norm, $setting->border);
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Ruang Perawatan', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, $registrasi->jns_perawatan->name.' - '.$registrasi->ruang_perawatan->name, $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Nama Pasien', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, strtoupper($registrasi->pasien->nama), $setting->border);
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Kelas Perawatan', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, '', $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Dokter', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->dokter->name, $setting->border);
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Penjamin', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, strtoupper($registrasi->golpas->r_grouppas->name.' - '.$registrasi->golpas->name), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Tanggal Masuk', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, LibApp::dateHuman($registrasi->tglReg), $setting->border);
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Tanggal Keluar', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, LibApp::dateHuman($registrasi->tglReg), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $widthTanggal = 19;
        $widthUraian = 89;
        $widthKelas = 15;
        $widthHarga = 19;
        $widthQty = 10;
        $pdf->SetFont('arial', 'b', 8.5);
        $pdf->Cell($widthUraian, $setting->heightCell, 'Uraian Pelayanan', $setting->border);
        $pdf->Cell($widthTanggal, $setting->heightCell, 'Tanggal', $setting->border);
        $pdf->Cell($widthKelas, $setting->heightCell, 'Kelas', $setting->border);
        $pdf->Cell($widthHarga, $setting->heightCell, 'Satuan', $setting->border, '', 'R');
        $pdf->Cell($widthQty, $setting->heightCell, 'Qty', $setting->border, '', 'C');
        $pdf->Cell($widthHarga, $setting->heightCell, 'Discount', $setting->border, '', 'R');
        $pdf->Cell($widthHarga, $setting->heightCell, 'Biaya', $setting->border, '', 'R');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln(3);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $setting->heightCellData = $setting->heightCell+0.5;

        $total = 0 ;
        foreach ($data as $row ) {
            $pdf->Cell(4, $setting->heightCellData, '', $setting->border);
            $pdf->Cell($widthUraian-4, $setting->heightCellData, strtoupper($row->r_tarif_harga->r_tarif->name), $setting->border);
            $pdf->Cell($widthTanggal, $setting->heightCellData, LibApp::dateHuman($row->tgl_tindakan), $setting->border);
            $pdf->Cell($widthKelas, $setting->heightCellData, '', $setting->border);
            $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga), $setting->border, '', 'R');
            $pdf->Cell($widthQty, $setting->heightCellData, number_format($row->qty), $setting->border, '', 'C');
            $pdf->Cell($widthHarga, $setting->heightCellData, '0', $setting->border, '', 'R');
            $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga * $row->qty), $setting->border, '', 'R');
            $pdf->ln();
            foreach ($row->r_billing_jasa as $jasa ) {
                if( $jasa->r_pelaksana->group == 'dokter' ){
                    $pdf->ln(-1);
                    $pdf->SetFont('arial', 'b', $setting->fontSize-0.5);
                    $pdf->Cell($setting->widthFull, $setting->heightCellData, '        '.$jasa->r_pelaksana->name, $setting->border);
                    $pdf->SetFont('arial', '', $setting->fontSize);
                    $pdf->ln(5);
                }
            }

            $total += ($row->r_tarif_harga->harga * $row->qty);

        }

        $pdf->ln(1);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);

        // Total Tagihan
        $pdf->Cell($setting->widthFull-25, $setting->heightCellData, 'TOTAL', $setting->border, '', 'R');
        $pdf->Cell(25, $setting->heightCellData, number_format($total), $setting->border, '', 'R');
        $pdf->ln();
        $pdf->Cell($setting->widthFull-25, $setting->heightCellData, 'BIAYA ADMINISTRASI', $setting->border, '', 'R');
        $pdf->Cell(25, $setting->heightCellData, '0', $setting->border, '', 'R');
        $pdf->ln();
        $pdf->Cell($setting->widthFull-25, $setting->heightCellData, 'UANG MUKA', $setting->border, '', 'R');
        $pdf->Cell(25, $setting->heightCellData, '0', $setting->border, '', 'R');
        $pdf->ln();
        $pdf->Cell($setting->widthFull-25, $setting->heightCellData, 'PENGEMBALIAN UANG', $setting->border, '', 'R');
        $pdf->Cell(25, $setting->heightCellData, '0', $setting->border, '', 'R');
        $pdf->ln(5);
        $pdf->SetFont('arial', 'b', $setting->fontSize+1);
        $pdf->Cell($setting->widthFull-25, $setting->heightCellData+2, 'TOTAL TAGIHAN', $setting->border, '', 'R');
        $pdf->Cell(25, $setting->heightCellData+2, number_format($total), 'T', '', 'R');
        $pdf->SetFont('arial', '', $setting->fontSize);
        $pdf->ln();
        // End Total Tagihan

        // Footer
        $pdf->ln();
        $setting->widthCell = $setting->widthCell + 28;
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, $setting->kota.', '.date('d-m-Y'), $setting->border, '', 'C');
        $pdf->ln();
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'an. Kepala Rumah Sakit', $setting->border, '', 'C');
        $pdf->ln();
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Kasir,', $setting->border, '', 'C');
        $pdf->ln(18);
        $pdf->Cell($setting->widthCell+35, $setting->heightCell);
        $pdf->Cell($setting->widthCell-15, $setting->heightCell, '( HERI WENDI HARTONO )', $setting->border, '', 'C');
        // End of Footer

		$pdf->Output();
        exit;
    }
}


// // Start Data Row
// $pdf->SetFont('arial', 'b', 8.5);
// $pdf->ln(1);
// $pdf->Cell($setting->widthFull, $setting->heightCell+1, 'A. KAMAR PERAWATAN', 'B');
// $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
// $pdf->ln(6);
// $setting->heightCellData = $setting->heightCell;
// $pdf->Cell(4, $setting->heightCellData, '', $setting->border);
// $pdf->Cell($widthUraian-4, $setting->heightCellData, 'WIRA 1 Bed 2', $setting->border);
// $pdf->Cell($widthTanggal, $setting->heightCellData, '02-06-2022', $setting->border);
// $pdf->Cell($widthKelas, $setting->heightCellData, 'III', $setting->border);
// $pdf->Cell($widthHarga, $setting->heightCellData, '350,000', $setting->border, '', 'R');
// $pdf->Cell($widthQty, $setting->heightCellData, '1', $setting->border, '', 'C');
// $pdf->Cell($widthHarga, $setting->heightCellData, '0', $setting->border, '', 'R');
// $pdf->Cell($widthHarga, $setting->heightCellData, '350,000', $setting->border, '', 'R');
// $pdf->ln();
// $pdf->Cell(4, $setting->heightCellData, '', $setting->border);
// $pdf->Cell($widthUraian-4, $setting->heightCellData, 'WIRA 1 Bed 2', $setting->border);
// $pdf->Cell($widthTanggal, $setting->heightCellData, '02-06-2022', $setting->border);
// $pdf->Cell($widthKelas, $setting->heightCellData, 'III', $setting->border);
// $pdf->Cell($widthHarga, $setting->heightCellData, '350,000', $setting->border, '', 'R');
// $pdf->Cell($widthQty, $setting->heightCellData, '1', $setting->border, '', 'C');
// $pdf->Cell($widthHarga, $setting->heightCellData, '0', $setting->border, '', 'R');
// $pdf->Cell($widthHarga, $setting->heightCellData, '350,000', $setting->border, '', 'R');
// $pdf->ln();
// // subtotal
// $pdf->SetFont('arial', 'b', $setting->fontSize);
// $pdf->Cell($setting->widthFull, $setting->heightCellData+2, 'SUB TOTAL :     700,000', 'B,T', '', 'R'); // Border Only
// $pdf->SetFont('arial', '', $setting->fontSize);
// $pdf->ln($setting->fontSize);
// end of subtotal
// End Data Row

// Start Data Row
// $pdf->SetFont('arial', 'b', 8.5);
// $pdf->ln(1);
// $pdf->Cell($setting->widthFull, $setting->heightCell+1, 'B. VISIT DOKTER', 'B');
// $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
// $pdf->ln(6);
// // ---
// $pdf->Cell(4, $setting->heightCell, '', $setting->border);
// $pdf->Cell($widthUraian-4, $setting->heightCell, 'VISITE DOKTER SPESIALIS KARTIKA I-III', $setting->border);
// $pdf->Cell($widthTanggal, $setting->heightCell, '02-06-2022', $setting->border);
// $pdf->Cell($widthKelas, $setting->heightCell, 'III', $setting->border);
// $pdf->Cell($widthHarga, $setting->heightCell, '350,000', $setting->border, '', 'R');
// $pdf->Cell($widthQty, $setting->heightCell, '1', $setting->border, '', 'C');
// $pdf->Cell($widthHarga, $setting->heightCell, '0', $setting->border, '', 'R');
// $pdf->Cell($widthHarga, $setting->heightCell, '350,000', $setting->border, '', 'R');
// $pdf->ln(3.5);
// $pdf->Cell(7, $setting->heightCell, '', $setting->border);
// $pdf->SetFont('arial', 'b', $setting->fontSize);
// $pdf->Cell($widthUraian-4, $setting->heightCell, 'dr. Jeffry Harriadi Soewandi', $setting->border);
// $pdf->SetFont('arial', '', $setting->fontSize);
// $pdf->ln();
// ---

// ---
// $pdf->Cell(4, $setting->heightCell, '', $setting->border);
// $pdf->Cell($widthUraian-4, $setting->heightCell, 'VISITE DOKTER SPESIALIS KARTIKA I-III', $setting->border);
// $pdf->Cell($widthTanggal, $setting->heightCell, '02-06-2022', $setting->border);
// $pdf->Cell($widthKelas, $setting->heightCell, 'III', $setting->border);
// $pdf->Cell($widthHarga, $setting->heightCell, '350,000', $setting->border, '', 'R');
// $pdf->Cell($widthQty, $setting->heightCell, '1', $setting->border, '', 'C');
// $pdf->Cell($widthHarga, $setting->heightCell, '0', $setting->border, '', 'R');
// $pdf->Cell($widthHarga, $setting->heightCell, '350,000', $setting->border, '', 'R');
// $pdf->ln(3.5);
// $pdf->Cell(7, $setting->heightCell, '', $setting->border);
// $pdf->SetFont('arial', 'b', $setting->fontSize);
// $pdf->Cell($widthUraian-4, $setting->heightCell, 'dr. Jeffry Harriadi Soewandi', $setting->border);
// $pdf->SetFont('arial', '', $setting->fontSize);
// $pdf->ln();
// ---
// subtotal
// $pdf->SetFont('arial', 'b', $setting->fontSize);
// $pdf->Cell($setting->widthFull, $setting->heightCellData+2, 'SUB TOTAL :     700,000', 'B,T', '', 'R');
// $pdf->SetFont('arial', '', $setting->fontSize);
// $pdf->ln($setting->fontSize);
// end of subtotal
// End Data Row