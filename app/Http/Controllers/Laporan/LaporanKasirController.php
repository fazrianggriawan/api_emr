<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Printer\HeaderPrint;
use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Billing_pembayaran;
use App\Models\Billing_pembayaran_detail;
use App\Models\Lab_hasil_pemeriksaan;
use App\Models\Lab_nama_hasil_rujukan;
use App\Models\Lab_nilai_rujukan_options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class LaporanKasirController extends BaseController
{
    public function TransaksiKasir($tanggal, $caraBayar, $jnsPerawatan)
    {
        $this->tanggal = $tanggal.' 12:00:00';
        $this->caraBayar = $caraBayar;
        $this->jnsPerawatan = $jnsPerawatan;

        $data =  Billing_pembayaran::with(
                    [
                        'r_registrasi' => function($q){
                            return $q->with(['pasien','ruang_perawatan']);
                        },
                        'r_cara_bayar'
                    ])
                    ->whereHas('r_registrasi', function($q){
                        return $q->where('id_jns_perawatan', $this->jnsPerawatan);
                    })
                    ->where('dateCreated', '<=', $this->tanggal)
                    ->where('id_cara_bayar', $this->caraBayar)
                    ->where('active', 1)
                    ->get();

        $data = collect($data)->groupBy('r_registrasi.ruang_perawatan.name');

        return $this->GoPrint($data, 'kasir');
    }

    public static function GoPrint($data, $username)
    {
        try {
            $user = App_user::where('username', $username)->first();

            $pdf = new PDFBarcode();

            $pdf->AddPage('P', 'A4', 0);

            $header = new HeaderPrint();
            $setting = $header->GetSetting( new stdClass() );
            $setting->border = 0;

            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->Cell($setting->widthFull, $setting->heightCell, 'LAPORAN BILLING - RAWAT JALAN', 'B');
            $pdf->ln();
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

            $widthNota = 30;
            $widthTanggal = 25;
            $widthJam = 18;
            $widthNorm = 15;
            $widthPembayaran = 32;
            $widthName = 50;
            $widthJumlah = 20;

            $pdf->Cell($widthNota, $setting->heightCell, 'No. Nota', $setting->border);
            $pdf->Cell($widthTanggal, $setting->heightCell, 'Tanggal', $setting->border);
            $pdf->Cell($widthJam, $setting->heightCell, 'Jam', $setting->border);
            $pdf->Cell($widthNorm, $setting->heightCell, 'No.RM', $setting->border);
            $pdf->Cell($widthName, $setting->heightCell, 'Nama Pasien', $setting->border);
            $pdf->Cell($widthPembayaran, $setting->heightCell, 'Jns.Pembayaran', $setting->border);
            $pdf->Cell($widthJumlah, $setting->heightCell, 'Jumlah', $setting->border);
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->ln();
            $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
            $pdf->ln();

            $setting->heightCellData = $setting->heightCell;

            $total = 0 ;
            foreach ($data as $key => $value ) {
                $pdf->Cell($setting->widthFull, $setting->heightCell+1, strtoupper($key), 'B');
                $pdf->ln();
                $subtotal = 0;
                foreach ($value as $row) {
                    $pdf->Cell($widthNota, $setting->heightCellData, $row->no_pembayaran, $setting->border);
                    $pdf->Cell($widthTanggal, $setting->heightCellData, Libapp::dateHuman(substr($row->dateCreated, 0, 10)), $setting->border);
                    $pdf->Cell($widthJam, $setting->heightCellData, substr($row->dateCreated, 11, 8), $setting->border);
                    $pdf->Cell($widthNorm, $setting->heightCellData, $row->r_registrasi->pasien->norm, $setting->border);
                    $pdf->Cell($widthName, $setting->heightCellData, strtoupper($row->r_registrasi->pasien->nama), $setting->border);
                    $pdf->Cell($widthPembayaran, $setting->heightCellData, strtoupper($row->r_cara_bayar->name), $setting->border);
                    $pdf->Cell($widthJumlah, $setting->heightCellData, number_format($row->jumlah), $setting->border);
                    $pdf->ln();

                    $total += $row->jumlah;
                    $subtotal += $row->jumlah;
                }
                $pdf->SetFont('arial', 'B', $setting->fontSize);
                $pdf->Cell($setting->widthFull-52, $setting->heightCell+1, '', 'T');
                $pdf->Cell(32, $setting->heightCell+1, 'SUB-TOTAL', 'T');
                $pdf->Cell(20, $setting->heightCell+1, number_format($subtotal), 'T');
                $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
                $pdf->ln();
            }

            $pdf->ln(1);
            $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
            $pdf->ln(4);

            // Total Tagihan
            $pdf->Cell($setting->widthFull-25, $setting->heightCellData, 'TOTAL', $setting->border, '', 'R');
            $pdf->Cell(25, $setting->heightCellData, number_format($total), $setting->border, '', 'R');
            $pdf->ln();
            // End Total Tagihan

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
