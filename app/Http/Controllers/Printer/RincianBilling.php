<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Billing;
use App\Models\Billing_detail;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class RincianBilling extends BaseController
{
    public function ByBillingHead($noreg, $username, $idBillingHead)
    {
        Billing_detail::$noreg = $noreg;
        Billing_detail::$idBillingHead = $idBillingHead;
        $data = Billing_detail::GetBilling();
        $this->GoPrint($data, $noreg, $username);
    }

    public function ByPembayaran($noreg, $username, $idBillingPembayaran)
    {
        Billing_detail::$noreg = $noreg;
        Billing_detail::$idBillingPembayaran = $idBillingPembayaran;
        $data = Billing_detail::GetBilling();
        $this->GoPrint($data, $noreg, $username);
    }

    public function ByNoreg($noreg, $username)
    {
        Billing_detail::$noreg = $noreg;
        $data = Billing_detail::GetBilling();
        $this->GoPrint($data, $noreg, $username);
    }

    public function GoPrint($data, $noreg, $username)
    {
        try {
            $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();
            $user = App_user::where('username', $username)->first();

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
                $pdf->Cell($widthTanggal, $setting->heightCellData, LibApp::dateHuman($row->tanggal), $setting->border);
                $pdf->Cell($widthKelas, $setting->heightCellData, '', $setting->border);
                $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga), $setting->border, '', 'R');
                $pdf->Cell($widthQty, $setting->heightCellData, number_format($row->qty), $setting->border, '', 'C');
                $pdf->Cell($widthHarga, $setting->heightCellData, '0', $setting->border, '', 'R');
                $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga * $row->qty), $setting->border, '', 'R');
                $pdf->ln();
                if( $row->r_billing_detail_jasa ){
                    foreach ($row->r_billing_detail_jasa as $jasa ) {
                        if( $jasa->group == 'dokter' && $jasa->r_pelaksana ){
                            $pdf->ln(-1);
                            $pdf->SetFont('arial', 'b', $setting->fontSize-0.5);
                            $pdf->Cell($setting->widthFull, $setting->heightCellData, '        '.$jasa->r_pelaksana->name, $setting->border);
                            $pdf->SetFont('arial', '', $setting->fontSize);
                            $pdf->ln(5);
                        }
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

            $pdf->SetY($pdf->GetY() - 31);
            $pdf->SetX(0);

            // Footer
            $pdf->ln();
            $setting->widthCell = $setting->widthCell + 28;
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, $setting->kota.', '.LibApp::dateHuman(date('Y-m-d')), $setting->border, '', 'C');
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'an. Kepala Rumah Sakit', $setting->border, '', 'C');
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Kasir,', $setting->border, '', 'C');
            $pdf->ln(15);
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, '( '.strtoupper($user->name).' )', $setting->border, '', 'C');
            // End of Footer

            $pdf->Output();
            exit;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }

    }
}
