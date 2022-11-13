<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\Farmasi_billing;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class BillingFarmasi extends BaseController
{
    public function GoPrint($noreg)
    {
        $data = Farmasi_billing::where('noreg', $noreg)->where('active', 1)->get();
        $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();

        $pdf = new PDFBarcode();

		$pdf->AddPage('P', [105, 150], 0);

        $pdf->SetMargins(2,2);
        $pdf->SetY(0);
        $pdf->SetX(0);

        $header = new HeaderPrint();
        $pdf = $header->GetHeaderSmall($pdf);
        $setting = $header->GetSetting( new stdClass() );

        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($setting->widthCell+45, $setting->heightCell, 'APOTIK SALAK', $setting->border);
        $pdf->ln(3);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize-1);

        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $setting->heightCell = $setting->heightCell - 1;
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'No. Reg / No. RM', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->noreg.' / '.$registrasi->pasien->norm, $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Nama Pasien', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, strtoupper($registrasi->pasien->nama), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Dokter', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->dokter->name, $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Tgl. Registrasi', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, LibApp::dateHuman($registrasi->tglReg), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $widthNo = 6;
        $widthTanggal = 11;
        $widthUraian = 45;
        $widthKelas = 15;
        $widthHarga = 14;
        $widthQty = 10;
        $pdf->SetFont('arial', 'b', $setting->fontSize-1.5);
        $pdf->Cell($widthNo, $setting->heightCell, 'NO.', $setting->border);
        $pdf->Cell($widthUraian, $setting->heightCell, 'NAMA OBAT', $setting->border);
        $pdf->Cell($widthTanggal, $setting->heightCell, 'SATUAN', $setting->border);
        $pdf->Cell($widthQty, $setting->heightCell, 'QTY', $setting->border, '', 'C');
        $pdf->Cell($widthHarga, $setting->heightCell, 'HARGA', $setting->border, '', 'R');
        $pdf->Cell($widthHarga, $setting->heightCell, 'JUMLAH', $setting->border, '', 'R');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln(3);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $setting->heightCellData = $setting->heightCell+0.5;

        $total = 0 ;
        $i = 1;
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize-1.5);
        foreach ($data as $row ) {
            $pdf->Cell($widthNo, $setting->heightCell, $i++.'.', $setting->border);
            $pdf->Cell($widthUraian, $setting->heightCell, $row->nama_obat, $setting->border);
            $pdf->Cell($widthTanggal, $setting->heightCell, $row->satuan, $setting->border);
            $pdf->Cell($widthQty, $setting->heightCell, $row->qty, $setting->border, '', 'C');
            $pdf->Cell($widthHarga, $setting->heightCell, number_format($row->harga), $setting->border, '', 'R');
            $pdf->Cell($widthHarga, $setting->heightCell, number_format($row->harga * $row->qty), $setting->border, '', 'R');
            $pdf->ln();

            $total += ($row->harga * $row->qty);
        }
        $pdf->ln(1);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $pdf->SetFont('arial', 'b', $setting->fontSize);
        // $pdf->Cell($setting->widthCell, $setting->heightCellData, 'TOTAL : '.number_format($total), 1, 'R');
        $pdf->Cell($setting->widthCell+26, $setting->heightCellData, 'TOTAL : ', $setting->border, '', 'R');
        $pdf->Cell($setting->widthCell-40, $setting->heightCellData, number_format($total), $setting->border, '', 'R');
        $pdf->ln(4);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize-1);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Tanggal Print : '.date('d/m/Y H:i:s'), $setting->border);

		$pdf->Output();
        exit;
    }
}