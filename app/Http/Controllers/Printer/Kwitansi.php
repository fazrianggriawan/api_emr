<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Billing_detail;
use App\Models\Billing_pembayaran_rincian;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class Kwitansi extends BaseController
{

    public function WithRincian($noreg, $username)
    {
        Billing_detail::$noreg = $noreg;
        $data = Billing_detail::GetBilling();
        $this->GoPrint($data, $noreg, $username, TRUE);
    }

    public function GoPrint($data, $noreg, $username, $withRincianBilling)
    {
        try {
            $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();
            $user = App_user::where('username', $username)->first();

            $pdf = new PDFBarcode();

            $pdf->AddPage('P', 'A4', 0);

            $header = new HeaderPrint();
            $setting = $header->GetSetting( new stdClass() );
            $setting->fontSize = $setting->fontSize+1;

            $startPos = 123;

            $pdf->ln(35);
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->SetLeftMargin($pdf->GetX()+$startPos);
            $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'JAM/TANGGAL', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell-20, $setting->heightCell, date('d M Y, H:i:s'), $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'NO. NOTA', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell-20, $setting->heightCell, $registrasi->noreg, $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'NO. RM', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell-20, $setting->heightCell, $registrasi->pasien->norm, $setting->border);
            $pdf->ln(2);
            $pdf->SetLeftMargin($pdf->GetX()-$startPos);
            $pdf->ln();
            // $pdf->Cell($setting->widthCell-25, $setting->heightCell, 'Terima Dari', $setting->border);
            // $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            // $pdf->Cell($setting->widthFull-35, $setting->heightCell, strtoupper($registrasi->pasien->nama), $setting->border);
            // $pdf->ln();
            $pdf->Cell($setting->widthCell-25, $setting->heightCell, 'Untuk Pembayaran ', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthFull-50, $setting->heightCell, 'BIAYA PERAWATAN '.$registrasi->jns_perawatan->name.'. ('.$registrasi->ruang_perawatan->name.')', $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-25, $setting->heightCell, 'Atas Nama Pasien', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthFull-50, $setting->heightCell, $registrasi->pasien->nama, $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-25, $setting->heightCell, 'Alamat Pasien', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthFull-50, $setting->heightCell, strtoupper($registrasi->pasien->alamat), $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-25, $setting->heightCell, 'Jenis Pembayaran', $setting->border);
            $pdf->Cell(5, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthFull-50, $setting->heightCell, $registrasi->golpas->r_grouppas->name.'-'.$registrasi->golpas->name, $setting->border);
            $pdf->ln(7);

            $pdf->SetFont('arial', 'b', $setting->fontSize);
            $pdf->Cell($setting->widthCell+45, $setting->heightCell, 'RINCIAN BIAYA PERAWATAN', $setting->border);
            $pdf->ln();
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

            $widthTanggal = 19;
            $widthUraian = 120;
            $widthKelas = 15;
            $widthHarga = 19;
            $widthQty = 10;
            $pdf->SetFont('arial', 'b', $setting->fontSize);
            $pdf->Cell($widthUraian, $setting->heightCell, 'Uraian Pelayanan', $setting->border);
            $pdf->Cell($widthHarga, $setting->heightCell, 'Harga', $setting->border, '', 'R');
            $pdf->Cell($widthQty, $setting->heightCell, 'Qty', $setting->border, '', 'C');
            $pdf->Cell($widthHarga, $setting->heightCell, 'Discount', $setting->border, '', 'R');
            $pdf->Cell($widthHarga, $setting->heightCell, 'Jumlah', $setting->border, '', 'R');
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->ln(3);
            $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
            $pdf->ln(3);

            $setting->heightCellData = $setting->heightCell-0.5;

            $total = 0 ;
            foreach ($data as $row ) {
                $pdf->Cell(4, $setting->heightCellData, '', $setting->border);
                $pdf->Cell($widthUraian-4, $setting->heightCellData, strtoupper($row->r_tarif_harga->r_tarif->name), $setting->border);
                $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga), $setting->border, '', 'R');
                $pdf->Cell($widthQty, $setting->heightCellData, number_format($row->qty), $setting->border, '', 'C');
                $pdf->Cell($widthHarga, $setting->heightCellData, '0', $setting->border, '', 'R');
                $pdf->Cell($widthHarga, $setting->heightCellData, number_format($row->r_tarif_harga->harga * $row->qty), $setting->border, '', 'R');
                $pdf->ln(2);
                if( $row->r_billing_detail_jasa ){
                    foreach ($row->r_billing_detail_jasa as $jasa ) {
                        if( $jasa->group == 'dokter' && $jasa->r_pelaksana ){
                            $pdf->SetFont('arial', 'b', $setting->fontSize-0.5);
                            $pdf->Cell($setting->widthFull, $setting->heightCellData+3, '     '.$jasa->r_pelaksana->name, $setting->border);
                            $pdf->SetFont('arial', '', $setting->fontSize);
                            $pdf->ln(3);
                        }
                    }
                }
                $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
                $pdf->ln();

                $total += ($row->r_tarif_harga->harga * $row->qty);

            }

            $rincianPembayaran = Billing_pembayaran_rincian::where('noreg', $noreg)->where('active', 1)->get();

            foreach ($rincianPembayaran as $row) {
                if( $row->kode != 'total' ){
                    $pdf->Cell($setting->widthFull-25, $setting->heightCellData, strtoupper($row->keterangan), $setting->border, '', 'R');
                    $pdf->Cell(25, $setting->heightCellData, number_format($row->jumlah), $setting->border, '', 'R');
                    $pdf->ln();
                    $total += $row->jumlah;
                }
            }

            $pdf->ln();
            // Total Tagihan
            $pdf->SetFont('arial', 'b', $setting->fontSize);
            $pdf->Cell(143, $setting->heightCellData);
            $pdf->Cell($setting->widthCell-35, $setting->heightCellData+2, 'TOTAL Rp. ', 'B');
            $pdf->Cell(25, $setting->heightCellData+2, number_format($total).'    ', 'B', '', 'R');
            $pdf->ln();
            // End Total Tagihan

            $pdf->SetY($pdf->GetY()-13);
            $pdf->SetFont('arial', 'B', $setting->fontSize);

            $pdf->ln();
            $pdf->Cell($setting->widthCell, $setting->heightCell, ' Terbilang :', $setting->border);
            $pdf->ln();
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->MultiCell($setting->widthFull-50, $setting->heightCell, strtoupper(LibApp::terbilang($total).'Rupiah'), $setting->border);

            // Footer
            $pdf->SetLeftMargin($pdf->GetX()+$startPos);
            $pdf->ln(1);
            $setting->widthCell = $setting->widthCell + 28;
            $pdf->Cell($setting->widthCell-15, $setting->heightCell-1, $setting->kota.', '.LibApp::dateHuman(date('Y-m-d')), $setting->border, '', 'C');
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell-1, 'an. Kepala Rumah Sakit', $setting->border, '', 'C');
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell-1, 'Kasir,', $setting->border, '', 'C');
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
