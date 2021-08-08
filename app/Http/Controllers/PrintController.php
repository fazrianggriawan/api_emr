<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use PDF;

class PrintController extends BaseController
{
    public function farmasi()
    {
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $html = '
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style type="text/css">
                @page { margin: 0px; font-size: 24pt; }
                body { margin: 0px; font-size: 24pt; }
            </style>
            <div>
            <div class="col-7 bd p-p-3 bg-white rounded">
                    <div class="row p-mb-5" style="margin-bottom:20px;">
                        <div class="col tx-center" style="text-align:center;">
                            <h2>RSPAD GATOT SOEBROTO <br> INSTALASI FARMASI</h2>
            Jl. Dr. Abdul Rachman Saleh No.24 JAKARTA PUSAR <br>
                            Telp 021 3441008 Ext. 2135
            </div>
            </div>
            <div class="row p-mb-3" style="border-bottom: 1px solid black; padding-bottom: 5px;">      
                <table>
                    <tr>
                        <td valign="top" width="150"><b>Ruang/Poliklinik :</b> Bedah Urologi</td>
                        <td valign="top"><b>Tanggal :</b> 12 Jul 2021 <br> <b>Riwayat Alergi Obat :</b> panadol, amoxan,</td>
                    </tr>
                </table>                                                                 
            </div>
            <div class="row p-mb-4" style="margin-top: 20px;">
                <div class="col">
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                </div>
            </div>
            <table>
                <tr>
                    <td valign="top">
                        <div style="border: 1px solid black; padding: 5px; margin-bottom: 5px; text-align: center;">
                            No. Antrian Farmasi :
                            <h2 style="margin:5px 0;" align="center">BU001</h2>
                        </div>
                        <table style="border:1px solid black; padding: 5px;">
                            <tr>
                                <td valign="top">Nama Pasien</td>
                                <td valign="top" width="10" class="tx-center">:</td>
                                <td>SIMRS MANDIRI</td>
                            </tr>
                            <tr>
                                <td valign="top">No. Rm</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>818181</td>
                            </tr>
                            <tr>
                                <td valign="top">Tgl.Lahir/Umur</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>1988-12-01 / 32 thn</td>
                            </tr>
                            <!-- <tr>
                                <td valign="top">Berat Badan</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>61 kg</td>
                            </tr> -->
                            <tr>
                                <td valign="top">Nama & SIP Dokter</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>dr. R. Bebet Prasetyo SpU. <br>SIP. 03/SIP/SDK/2018</td>
                            </tr>
                        </table>
                    </td>
                    <td valign="top">                        
                        <table class="table table-bordered table-info-resep p-mb-1" style="border: 1px solid black; margin-bottom: 5px; padding: 5px;">
                            <tr>
                                <td width="20" class="tx-center">V</td>
                                <td style="border: 1px solid black; padding: 10px 83px;"></td>
                            </tr>
                            <tr>
                                <td class="tx-center">H</td>
                                <td style="border: 1px solid black; padding: 10px 60px;"></td>
                            </tr>
                            <tr>
                                <td class="tx-center">D</td>
                                <td style="border: 1px solid black; padding: 10px 60px;"></td>
                            </tr>
                            <tr>
                                <td class="tx-center">S</td>
                                <td style="border: 1px solid black; padding: 10px 60px;"></td>
                            </tr>
                        </table>
                        <table class="table table-bordered table-info-resep" style="border:1px solid black; padding: 5px;">
                            <tr>
                                <td width="130">Benar Pasien</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Obat</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Dosis</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Waktu</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Cara Pemberian</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Informasi</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                            <tr>
                                <td>Benar Pendokumentasian</td>
                                <td style="border:1px solid black; padding: 5px 10px;"></td>
                            </tr>
                        </table>
                    </td>                    
                </tr>
            </table>
                                                        
            <div class="p-mt-3" style="padding-top:20px;"><small>Resep ini hanya berlaku di lingkungan RSPAD GATOT SOEBROTO</small></div>            
        </div></div>';
        $customPaper = array(0,0,360,360);
        $pdf->loadHTML($html)->setOptions(['dpi' => 96, 'defaultFont' => 'sans-serif', "default_media_type" => "print"])->setPaper($customPaper);
        return $pdf->stream();
    }

    public function laboratorium()
    {
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $html = '<div style="font-size: 12px;">
            <div class="col-7 bd p-p-3 bg-white rounded">
                    <div class="row p-mb-5" style="margin-bottom:20px;">
                        <div class="col tx-center" style="text-align:center;">
                            <h1 style="font-size:18px;">RSPAD GATOT SOEBROTO <br> LABORATORIUM</h1>
            Jl. Dr. Abdul Rachman Saleh No.24 JAKARTA PUSAR <br>
                            Telp 021 3441008 Ext. 2135
            </div>
            </div>
            <div class="row p-mb-3" style="border-bottom: 1px solid black; padding-bottom: 5px;">      
                <table>
                    <tr>
                        <td valign="top" width="150"><b>Ruang/Poliklinik :</b> Bedah Urologi</td>
                        <td valign="top"><b>Tanggal :</b> 12 Jul 2021</td>
                    </tr>
                </table>                                                                 
            </div>
            <div class="row p-mb-4" style="margin-top: 20px;">
                <div class="col">
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                </div>
            </div>
            <table>
                <tr>
                    <td valign="top" width="150">
                        <div style="border: 1px solid black; padding: 5px; margin-bottom: 5px; text-align: center;">
                            No. Antrian Laboratorium :
                            <h2 style="margin:5px 0;" align="center">BU001</h2>
                        </div>
                        
                    </td>
                    <td valign="top">
                    <table style="border:1px solid black; padding: 5px;">
                            <tr>
                                <td valign="top">Nama Pasien</td>
                                <td valign="top" width="10" class="tx-center">:</td>
                                <td>SIMRS MANDIRI</td>
                            </tr>
                            <tr>
                                <td valign="top">No. Rm</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>818181</td>
                            </tr>
                            <tr>
                                <td valign="top">Tgl.Lahir/Umur</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>1988-12-01 / 32 thn</td>
                            </tr>
                            <!-- <tr>
                                <td valign="top">Berat Badan</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>61 kg</td>
                            </tr> -->
                            <tr>
                                <td valign="top">Nama & SIP Dokter</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>dr. R. Bebet Prasetyo SpU. <br>SIP. 03/SIP/SDK/2018</td>
                            </tr>
                        </table>                                                
                    </td>                    
                </tr>
            </table>                        
        </div></div>';
        $pdf->loadHTML($html)->setOptions(['dpi' => 80, 'defaultFont' => 'sans-serif', "default_media_type" => "print"])->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function radiologi()
    {
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $html = '
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style type="text/css">
                @page { margin: 0px; font-size: 24pt; }
                body { margin: 0px; font-size: 24pt; }
            </style>
            <div>
            <div class="col-7 bd p-p-3 bg-white rounded">
                    <div class="row p-mb-5" style="margin-bottom:20px;">
                        <div class="col tx-center" style="text-align:center;">
                            <h1>RSPAD GATOT SOEBROTO <br> RADIOLOGI</h1>
            Jl. Dr. Abdul Rachman Saleh No.24 JAKARTA PUSAR <br>
                            Telp 021 3441008 Ext. 2135
            </div>
            </div>
            <div class="row p-mb-3" style="border-bottom: 1px solid black; padding-bottom: 5px;">      
                <table>
                    <tr>
                        <td valign="top" width="150"><b>Ruang/Poliklinik :</b> Bedah Urologi</td>
                        <td valign="top"><b>Tanggal :</b> 12 Jul 2021</td>
                    </tr>
                </table>                                                                 
            </div>
            <div class="row p-mb-4" style="margin-top: 20px;">
                <div class="col">
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                    <div *ngFor="let item of dataObatNonRacikan; let i = index" class="bd-b p-pb-2 p-mb-2" style="border-bottom: 1px solid black; padding-bottom: 5px; margin-bottom: 10px;">
                        <div>
                            <span class="tx-bold" style="font-size: 16px;">R/</span> &nbsp; PANADOL BIRU (TABLET) No. IV
                        </div>
                        <div class="p-pl-6" style="padding-left: 50px;">S 1 Tab Makan 2x Sehari Sesudah makan 2 hari</div>                        
                    </div>
                </div>
            </div>
            <table>
                <tr>
                    <td valign="top" width="150">
                        <div style="border: 1px solid black; padding: 5px; margin-bottom: 5px; text-align: center;">
                            No. Antrian Radiologi :
                            <h2 style="margin:5px 0;" align="center">BU001</h2>
                        </div>
                        
                    </td>
                    <td valign="top">
                    <table style="border:1px solid black; padding: 5px;">
                            <tr>
                                <td valign="top">Nama Pasien</td>
                                <td valign="top" width="10" class="tx-center">:</td>
                                <td>SIMRS MANDIRI</td>
                            </tr>
                            <tr>
                                <td valign="top">No. Rm</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>818181</td>
                            </tr>
                            <tr>
                                <td valign="top">Tgl.Lahir/Umur</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>1988-12-01 / 32 thn</td>
                            </tr>
                            <!-- <tr>
                                <td valign="top">Berat Badan</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>61 kg</td>
                            </tr> -->
                            <tr>
                                <td valign="top">Nama & SIP Dokter</td>
                                <td valign="top" class="tx-center">:</td>
                                <td>dr. R. Bebet Prasetyo SpU. <br>SIP. 03/SIP/SDK/2018</td>
                            </tr>
                        </table>                                                
                    </td>                    
                </tr>
            </table>                        
        </div></div>';
        $pdf->loadHTML($html)->setOptions(['dpi' => 80, 'defaultFont' => 'sans-serif', "default_media_type" => "print"])->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function tcpdf()
    {
        PDF::SetTitle('Hello World');
        PDF::AddPage();

        $border = 0;
        $fontLg = 22;
        $fontMd = 18;
        $fontSm = 16;

        PDF::SetFont('', '', $fontLg, '', true);

        $html =
            <<<EOD
                <p align="center"><b>RSPAD GATOT SOEBROTO <br/> INSTALASI FARMASI</b></p>            
            EOD;
        PDF::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        PDF::SetFont('', '', $fontMd, '', true);
        $html =
            <<<EOD
                <p align="center">Jl. Dr. Abdul Rachman Saleh No.24 Jakarta Pusat <br/>Telp 021 3441008 Ext. 2135</p>            
            EOD;
        PDF::writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
        PDF::Ln(8);
        PDF::MultiCell(90, 5, 'Ruangan/Poliklinik : ', $border, 'L', 0, 0, '', '', true);
        $html = '12 Jul 2021';
        PDF::MultiCell(90, 5, 'Tanggal : '.$html, $border, 'L', 0, 0, '', '', true);
        PDF::Ln(8);
        PDF::MultiCell(90, 5, 'Bedah Urologi', $border, 'L', 0, 0, '', '', true);
        PDF::MultiCell(90, 5, 'Riwayat Alergi Obat :', $border, 'L', 0, 0, '', '', true);
        PDF::Ln(8);
        PDF::MultiCell(89, 5, 'Amoxa, Paracetamol, Asdalk, askldjaslkd, askdalksjds', 1, 'L', 0, 0, 103, '', true);
        PDF::Ln(25);
        PDF::MultiCell(182, 5, 'R/ PARACETAMOL 500 MG (TABLET) No. LV', $border, 'L', 0, 0, '', '', true);
        PDF::SetFont('', '', $fontMd, '', true);
        PDF::Ln(8);
        PDF::MultiCell(172, 5, 'S 0.1 Miligram Makan 1x Sehari Sebelum makan 1 hari', $border, 'L', 0, 0, 20, '', true);
        PDF::Ln(8);
        PDF::Cell(182, 0.5, '', 0, 'L', 0, 1, '', '', true);
        PDF::Ln(3);
        PDF::MultiCell(182, 5, 'R/ AMOXAN CAPS 500 (CAPSUL) No. LVIII', $border, 'L', 0, 0, '', '', true);
        PDF::SetFont('', '', $fontMd, '', true);
        PDF::Ln(8);
        PDF::MultiCell(172, 5, 'S 1 Tab Makan 3x Sehari Sebelum makan 1 bulan', $border, 'L', 0, 0, 20, '', true);
        PDF::Ln(8);
        PDF::Cell(182, 0.5, '', 0, 'L', 0, 1, '', '', true);
        PDF::Ln(3);
        PDF::MultiCell(182, 5, 'R/ AMOXAN CAPS 500 (CAPSUL) No. LVIII', $border, 'L', 0, 0, '', '', true);
        PDF::SetFont('', '', $fontMd, '', true);
        PDF::Ln(8);
        PDF::MultiCell(172, 5, 'S 1 Tab Makan 3x Sehari Sebelum makan 1 bulan', $border, 'L', 0, 0, 20, '', true);
        PDF::Ln(8);
        PDF::Cell(182, 0.5, '', 0, 'L', 0, 1, '', '', true);
        PDF::Ln(3);
        PDF::Ln(8);
        PDF::setCellPaddings(0, 3, 0, 3);
        PDF::writeHTMLCell(0, 0, '', '', 'No. Antrian Farmasi <br><b style="font-size:'.$fontLg.'pt;">BU001</b>', 1, 1, 0, true, 'C', true);
        PDF::Ln(3);
        PDF::setCellPaddings(0, 0, 0, 0);
        $table = <<<EOD
                <table border="0">
                    <tr>
                        <td width="353">
                            <table border="0" cellpadding="3" style="border: 1px solid black;">
                                <tr>
                                    <td width="125">Nama Pasien</td>
                                    <td width="220">: LIM KHUN SENG</td>
                                </tr>
                                <tr>
                                    <td>No. RM</td>
                                    <td>: 959683</td>
                                </tr>
                                <tr>
                                    <td>Tgl.Lahir</td>
                                    <td>: 1951-01-09</td>
                                </tr>
                                <tr>
                                    <td>Umur</td>
                                    <td>: 70 thn</td>
                                </tr>
                                <tr>
                                    <td>Nama Dokter</td>
                                    <td>: dr. R. Bebet Prasetyo SpU.</td>
                                </tr>
                                <tr>
                                    <td>SIP Dokter</td>
                                    <td>: SIP.03/SIP/SDK/2018</td>
                                </tr>
                            </table>                            
                        </td>
                        <td width="185">
                            <table border="1" cellpadding="2">
                                <tr>
                                    <td width="20" align="center">V</td>
                                    <td width="150"></td>
                                </tr>
                                <tr>
                                    <td align="center">H</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td align="center">D</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td align="center">S</td>
                                    <td></td>
                                </tr>
                            </table>   
                            <br><br>                         
                            <table border="1" cellpadding="2">
                                <tr>
                                    <td width="150">Benar Pasien</td>
                                    <td width="20"></td>
                                </tr>
                                <tr>
                                    <td>Benar Obat</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Benar Dosis</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Benar Waktu</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Benar Cara Pemberian</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Benar Informasi</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Benar Pendokumentasian</td>
                                    <td></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                            
            EOD;
        PDF::writeHTMLCell(0, 0, '', '', $table, 0, 1, 0, true, 'L', true);
        PDF::Ln(8);


//        PDF::MultiCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

        PDF::Output('hello_world.pdf');
    }
}
