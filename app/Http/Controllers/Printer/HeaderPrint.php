<?php

namespace App\Http\Controllers\Printer;

use App\Models\Setting;
use DateTime;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

class HeaderPrint extends BaseController
{
    public function GetSetting($class)
    {
        $class->kota        = 'Bogor';
        $class->fontSize    = 9;
        $class->border      = 0;
        $class->fontWeight  = '';
        $class->heightCell  = 5;
		$class->widthCell   = 57;
        $class->widthFull = $class->widthCell+133;

        return $class;
    }

    public function GetHeader($pdf)
    {
        header("Content-type:application/pdf");

        $setting = self::GetSetting(new stdClass());

        $settingRs = Setting::find(1)->first();

        $logoWidth = 20;

        $pdf->Image('images/'.$settingRs->rs_logo, 23, 10, $logoWidth);

        $pdf->SetFont('arial', 'b', $setting->fontSize+5);
        $pdf->Cell($logoWidth+15);
        $pdf->MultiCell($setting->widthCell+66, $setting->heightCell+2, $settingRs->rs_name, $setting->border, 'C');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($logoWidth+23);
        $pdf->MultiCell($setting->widthCell+50, $setting->heightCell-1, $settingRs->rs_alamat, $setting->border, 'C');
        $pdf->SetFont('arial', $setting->fontWeight, $setting->fontSize);
        $pdf->Cell($setting->widthFull, 3, '', 'B');
        $pdf->ln(4);

        return $pdf;
    }
}
