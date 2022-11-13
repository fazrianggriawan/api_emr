<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Billing;
use App\Models\Lab_hasil_pemeriksaan;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;


class HasilLab extends BaseController
{
    public function GoPrint($noreg, $idBillingHead, $username)
    {
        $user = App_user::where('username', $username)->first();

        $data = Lab_hasil_pemeriksaan::with([
                'r_lab_nama_hasil_rujukan'=>function($q){
                    return $q->with(['r_nama_hasil', 'r_nilai_rujukan']);
                }])
                ->where('id_billing_head', $idBillingHead)
                ->where('active', 1)
                ->get();

        $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();

        $data = collect($data)->groupBy('r_lab_nama_hasil_rujukan.r_nama_hasil.category');


        $pdf = new PDFBarcode();

        // $qrcode = new QRcode('test aja', 'H');

        // $qrcode->displayHTML();

		$pdf->AddPage('P', 'A4', 0);

        $header = new HeaderPrint();
        // $pdf = $header->GetHeader($pdf);
        $setting = $header->GetSetting( new stdClass() );
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

        $pdf->Cell($setting->widthCell, $setting->heightCell, 'LABORATORIUM KLINIK', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'PASIEN '.strtoupper($registrasi->jns_perawatan->name), $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'RUMAH SAKIT TK.III 03.07.02 SALAK', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'JAM/TGL CETAK : '.date('d-m-Y/H:i:s'), $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Jl. Jenderal Sudirman No. 8 Bogor', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'NO.REGISTRASI : '.$registrasi->noreg, $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Telp. 0251-8344609 / 0251-8345222', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'NO.RM : '.$registrasi->pasien->norm, $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Email : rs_salak@yahoo.co.id', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'TGL. PERIKSA : '.$registrasi->tglReg, $setting->border);
        $pdf->ln(8);

        $pdf->Image('images/logo_salak.png', 85, 10, 30);

        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($setting->widthFull, $setting->heightCell, 'Dokter Penanggung Jawab Laboratorium : dr.Leliawati,M.Kes, SpPK, MH.Kes', $setting->border, 'C', 'C');
        $pdf->ln(4);
        $pdf->SetFont('arial', 'B', $setting->fontSize+1);
        $pdf->Cell($setting->widthFull, $setting->heightCell, 'HASIL PEMERIKSAAN LABORATORIUM', $setting->border, 'C', 'C');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln(4);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);

        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

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
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Usia/Jns. Kelamin', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, LibApp::getAge($registrasi->pasien->tgl_lahir).' TAHUN / '.strtoupper($registrasi->pasien->r_jns_kelamin->name), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Dokter', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->dokter->name, $setting->border);
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Penjamin', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, strtoupper($registrasi->golpas->r_grouppas->name.' - '.$registrasi->golpas->name), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $pdf->SetFont('arial', 'b', 8.5);
        $pdf->Cell($setting->widthCell+5, $setting->heightCell, 'PEMERIKSAAN', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'HASIL', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'NILAI RUJUKAN', $setting->border);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln(3);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $setting->heightCellData = $setting->heightCell;
        $rowStart = 75;
        foreach ($data as $key => $row ) {
            $pdf->SetFont('arial', 'B', $setting->fontSize);
            $pdf->Cell($setting->widthCell, $setting->heightCellData, strtoupper($key), $setting->border);
            $pdf->ln(5);
            $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
            foreach ($row as $value) {
                # code...
                $pdf->Cell(5, $setting->heightCellData, '', $setting->border);
                $pdf->Cell($setting->widthCell, $setting->heightCellData, strtoupper($value->r_lab_nama_hasil_rujukan->r_nama_hasil->name), $setting->border);
                $pdf->Cell($setting->widthCell, $setting->heightCellData, strtoupper($value->hasil), $setting->border);
                $pdf->Cell($setting->widthCell, $setting->heightCellData, $value->r_lab_nama_hasil_rujukan->r_nilai_rujukan->name, $setting->border);
                $pdf->ln(2.5);
                $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
                $pdf->ln(2.5);
                $rowStart += 6.1;
            }
            $pdf->ln();
        }

        // Footer
        $pdf->SetMargins(15, 0);
        $pdf->ln();
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Dokter Instalasi Laboratorium', $setting->border);
        $pdf->Cell($setting->widthCell+25, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Petugas Laboratorium', $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Rumkit TK.III 03.07.02 Salak', $setting->border);
        $pdf->ln(34);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'dr.Leliawati,M.Kes, SpPK, MH.Kes', $setting->border);
        $pdf->Cell($setting->widthCell+25, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, strtoupper($user->name), $setting->border);
        $pdf->ln();

        $pdf->GetQRCode($pdf, 'http://rssalakbogor.co.id/online/hasilLab/'.$registrasi->noreg, 20, $pdf->GetY()-32, 25);

        $pdf->SetY(-45);
        $pdf->Image('images/paripurna.png', 15, null, 50);
        $pdf->SetY(-47);
        $pdf->Image('images/blu.png', 170, null, 20);
        $pdf->SetMargins(10, 0);
        $pdf->SetY(-25);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only

        // End of Footer

		$pdf->Output();
        exit;
    }
}
