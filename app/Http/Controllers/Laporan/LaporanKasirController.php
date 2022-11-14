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
use App\Models\Mst_cara_bayar;
use App\Models\Mst_jns_perawatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class LaporanKasirController extends BaseController
{
    public function TransaksiKasir($base64)
    {
        $encode = base64_decode($base64);
        $json = json_decode($encode);

        $this->from = $json->from.' '.$json->timeFrom;
        $this->to = $json->to.' '.$json->timeTo;
        $this->caraBayar = $json->jnsPembayaran;
        $this->jnsPerawatan = $json->jnsPerawatan;

        $dataRaw =  Billing_pembayaran::with(
                    [
                        'r_registrasi' => function($q){
                            return $q->with(['pasien','ruang_perawatan','jns_perawatan']);
                        },
                        'r_cara_bayar'
                    ])
                    ->whereHas('r_registrasi', function($q){
                        return $q->where('id_jns_perawatan', $this->jnsPerawatan);
                    })
                    ->where(DB::raw('dateCreated'), '>=', $this->from)
                    ->where(DB::raw('dateCreated'), '<=', $this->to)
                    ->where('id_cara_bayar', $this->caraBayar)
                    ->where('active', 1)
                    ->get();

        if( count($dataRaw) > 0 ){
            $dataFirst = @$dataRaw[0];
            $data = collect($dataRaw)->groupBy('r_registrasi.ruang_perawatan.name');

            return $this->GoPrint($data, $json);
        }

    }

    public static function GoPrint($data, $json)
    {

        try {
            $jnsPembayaran = Mst_cara_bayar::where('id', $json->jnsPembayaran)->first();
            $jnsPerawatan = Mst_jns_perawatan::where('id', $json->jnsPerawatan)->first();
            $user = App_user::where('username', $json->username)->first();

            $pdf = new PDFBarcode();

            $pdf->AddPage('P', 'A4', 0);

            $header = new HeaderPrint();
            $pdf = $header->GetHeader($pdf);
            $setting = $header->GetSetting( new stdClass() );
            $setting->border = 0;

            $pdf->SetFont('arial', 'B', $setting->fontSize+1);
            $pdf->Cell($setting->widthFull, $setting->heightCell, 'LAPORAN TRANSAKSI BILLING', $setting->border);
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize-1);
            $pdf->ln();
            $setting->heightCell -= 1;
            $pdf->Cell($setting->widthCell-32, $setting->heightCell, 'Jenis Perawatan', $setting->border);
            $pdf->Cell(3, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell, $setting->heightCell, $jnsPembayaran->name, $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-32, $setting->heightCell, 'Pembayaran', $setting->border);
            $pdf->Cell(3, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell, $setting->heightCell, $jnsPerawatan->name, $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-32, $setting->heightCell, 'Periode Tanggal', $setting->border);
            $pdf->Cell(3, $setting->heightCell, ':', $setting->border, '', 'C');
            $pdf->Cell($setting->widthCell, $setting->heightCell, LibApp::dateHuman($json->from).' '.$json->timeFrom.' s.d. '.LibApp::dateHuman($json->to).' '.$json->timeTo, $setting->border);
            $pdf->ln();
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

            $widthNota = 30;
            $widthTanggal = 25;
            $widthJam = 18;
            $widthNorm = 15;
            $widthPembayaran = 32;
            $widthName = 50;
            $widthJumlah = 20;

            $setting->heightCell += 1;

            $pdf->Cell($setting->widthFull, 1, '', 'B');
            $pdf->ln();
            $pdf->Cell($widthNota, $setting->heightCell, 'No. Nota', $setting->border);
            $pdf->Cell($widthTanggal, $setting->heightCell, 'Tanggal', $setting->border);
            $pdf->Cell($widthJam, $setting->heightCell, 'Jam', $setting->border);
            $pdf->Cell($widthNorm, $setting->heightCell, 'No.RM', $setting->border);
            $pdf->Cell($widthName, $setting->heightCell, 'Nama Pasien', $setting->border);
            $pdf->Cell($widthPembayaran, $setting->heightCell, 'Jns.Pembayaran', $setting->border);
            $pdf->Cell($widthJumlah, $setting->heightCell, 'Jumlah', $setting->border);
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            $pdf->ln();
            $pdf->Cell($setting->widthFull, 1, '', 'T'); // Border Only
            $pdf->ln();

            $setting->heightCellData = $setting->heightCell;

            $total = $discount = 0 ;
            foreach ($data as $key => $value ) {
                $pdf->SetFont('arial', 'B', $setting->fontSize);
                $pdf->Cell($setting->widthFull, $setting->heightCell+1, strtoupper($key), 'B');
                $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
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
                $pdf->Cell(32, $setting->heightCell+1, 'TOTAL', 'T');
                $pdf->Cell(20, $setting->heightCell+1, number_format($subtotal), 'T');
                $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
                $pdf->ln();
            }

            $pdf->ln(1);
            $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
            $pdf->ln(4);

            // Total Tagihan
            $pdf->Cell($setting->widthFull-52, $setting->heightCellData, '', $setting->border);
            $pdf->Cell($setting->widthCell-25, $setting->heightCellData, 'SUB-TOTAL', $setting->border);
            $pdf->Cell(25, $setting->heightCellData, number_format($total), $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthFull-52, $setting->heightCellData, '', $setting->border);
            $pdf->Cell($setting->widthCell-25, $setting->heightCellData, 'DISKON', $setting->border);
            $pdf->Cell(25, $setting->heightCellData, number_format($discount), $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthFull-52, $setting->heightCellData, '', $setting->border);
            $pdf->Cell($setting->widthCell-25, $setting->heightCellData, 'TOTAL', $setting->border);
            $pdf->Cell(25, $setting->heightCellData, number_format($total - $discount), $setting->border);
            $pdf->ln();
            // End Total Tagihan

            // Footer
            $setting->heightCell -= 1;
            $setting->widthCell = $setting->widthCell + 28;
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Mengetahui', $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Bendahara Rumah Sakit', $setting->border);
            $pdf->ln(20);
            $pdf->Cell($setting->widthCell-30, $setting->heightCell, '', 'B');
            $pdf->ln(10);

            $pdf->SetLeftMargin($pdf->GetX()+100);
            $pdf->SetY($pdf->GetY()-32);
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, $setting->kota.', '.date('d M Y'), $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Dilaporkan Oleh', $setting->border);
            $pdf->ln();
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, 'Kasir,', $setting->border);
            $pdf->ln(18);
            $pdf->Cell($setting->widthCell-15, $setting->heightCell, '( '.strtoupper($user->name).' )', 'T');
            // End of Footer

            $pdf->Output();
            exit;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }

    }

}
