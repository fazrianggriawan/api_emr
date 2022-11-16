<?php

namespace App\Http\Controllers\Printer;

use App\Http\Libraries\LibApp;
use App\Http\Libraries\PDFBarcode;
use App\Models\App_user;
use App\Models\Billing_detail;
use App\Models\Billing_head;
use App\Models\Lab_hasil_pemeriksaan;
use App\Models\Radiologi_hasil_pemeriksaan;
use App\Models\Registrasi;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class LampiranKlaim extends BaseController
{
    public function Lampiran($noreg, $username)
    {
        $pdf = new PDFBarcode();
        $pdf = RincianBilling::ByNoreg($noreg, $username, $pdf);
        $pdf = self::HasilLab($noreg, $username, $pdf);
        $pdf = self::HasilRadiologi($noreg, $username, $pdf);
        $pdf->output();
        exit;
    }

    public static function HasilLab($noreg, $username, $pdf)
    {
        try {
            $hasil = Lab_hasil_pemeriksaan::where('noreg', $noreg)->groupBy('noreg')->where('active', 1)->get();
            foreach ($hasil as $row ) {
                $pdf = HasilLab::GoPrint($noreg, $row->id_billing_head, $username, $pdf);
            }
            return $pdf;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }

    }

    public static function HasilRadiologi($noreg, $username, $pdf)
    {
        try {
            $hasil = Radiologi_hasil_pemeriksaan::where('noreg', $noreg)->groupBy('noreg')->where('active', 1)->get();
            if( $hasil ){
                foreach ($hasil as $row ) {
                    $pdf = HasilRadiologi::GoPrint($noreg, $row->id_billing_head, $username, $pdf);
                }
            }
            return $pdf;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }

    }
}
