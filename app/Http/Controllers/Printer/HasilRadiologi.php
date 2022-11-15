<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Radiologi_hasil_pemeriksaan;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;


class HasilRadiologi extends BaseController
{
    public function GoPrint($noreg, $idBillingHead, $username)
    {
        $user = App_user::where('username', $username)->first();

        $data = Radiologi_hasil_pemeriksaan::with([
                'r_pelaksana',
                'r_billing_head' => function($q){
                    return $q->with('r_pelaksana');
                }])
                ->where('id_billing_head', $idBillingHead)
                ->where('active', 1)
                ->first();

        if(!$data){
            return 'Belum ada hasil';
        }

        $registrasi = Registrasi::GetAllData()->where('noreg', $noreg)->first();

        $pdf = new PDFBarcode();

		$pdf->AddPage('P', 'A4', 0);

        $header = new HeaderPrint();
        $setting = $header->GetSetting( new stdClass() );
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

        $pdf->Cell($setting->widthCell, $setting->heightCell, 'INSTALASI RADIOLOGI', $setting->border);
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
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'TGL. PERIKSA : '.LibApp::dateHuman($registrasi->tglReg), $setting->border);
        $pdf->ln(8);

        $pdf->Image('images/logo_salak.png', 85, 10, 30);

        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($setting->widthFull, $setting->heightCell, 'Dokter Penanggung Jawab Radiologi : '.$data->r_pelaksana->name, $setting->border, 'C', 'C');
        $pdf->ln(4);
        $pdf->SetFont('arial', 'B', $setting->fontSize+1);
        $pdf->Cell($setting->widthFull, $setting->heightCell, 'HASIL PEMERIKSAAN RADIOLOGI', $setting->border, 'C', 'C');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->ln(4);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(4);

        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);

        $setting->heightCell = $setting->heightCell - 1;
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'No. Reg / No. RM', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $registrasi->noreg.' / '.$registrasi->pasien->norm, $setting->border);
        $pdf->Cell($setting->widthCell-28, $setting->heightCell, 'Ruangan / Poliklinik', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, $registrasi->ruang_perawatan->name, $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Nama Pasien', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, strtoupper($registrasi->pasien->nama), $setting->border);
        $pdf->Cell($setting->widthCell-28, $setting->heightCell, 'Usia/Jns. Kelamin', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, LibApp::getAge($registrasi->pasien->tgl_lahir).' TAHUN / '.strtoupper(@$registrasi->pasien->r_jns_kelamin->name), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthCell-30, $setting->heightCell, 'Dokter', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+15, $setting->heightCell, $data->r_billing_head->r_pelaksana->name, $setting->border);
        $pdf->Cell($setting->widthCell-28, $setting->heightCell, 'Penjamin', $setting->border);
        $pdf->Cell(3, $setting->heightCell, ':', $setting->border);
        $pdf->Cell($setting->widthCell+1, $setting->heightCell, strtoupper($registrasi->golpas->r_grouppas->name.' - '.$registrasi->golpas->name), $setting->border);
        $pdf->ln();
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->MultiCell($setting->widthFull, $setting->heightCell, $pdf->WriteHTML($data->kesimpulan), $setting->border, 'L');
        $pdf->ln(3);
        $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only
        $pdf->ln(3);

        // Footer
        $pdf->SetMargins(15, 0);
        $pdf->ln();
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Dokter Instalasi Radiologi', $setting->border);
        $pdf->Cell($setting->widthCell+25, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Petugas Radiologi', $setting->border);
        $pdf->ln(4);
        $pdf->Cell($setting->widthCell, $setting->heightCell, 'Rumkit TK.III 03.07.02 Salak', $setting->border);
        $pdf->ln(34);
        $pdf->Cell($setting->widthCell, $setting->heightCell, $data->r_pelaksana->name, $setting->border);
        $pdf->Cell($setting->widthCell+25, $setting->heightCell, '', $setting->border);
        $pdf->Cell($setting->widthCell, $setting->heightCell, strtoupper($user->name), $setting->border);
        $pdf->ln();

        $pdf->GetQRCode($pdf, 'http://rssalakbogor.co.id/online/hasilRad/'.$data->id, 20, $pdf->GetY()-32, 25);

        // $pdf->SetY(-45);
        // $pdf->Image('images/paripurna.png', 15, null, 50);
        // $pdf->SetY(-47);
        // $pdf->Image('images/blu.png', 170, null, 20);
        // $pdf->SetMargins(10, 0);
        // $pdf->SetY(-25);
        // $pdf->Cell($setting->widthFull, 2, '', 'B'); // Border Only

        // End of Footer

		$pdf->Output();
        exit;
    }
}
