<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class PrintController extends BaseController
{
    public function farmasi()
    {
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $html = '<div style="font-size: 12px;">
            <div class="col-7 bd p-p-3 bg-white rounded">
                    <div class="row p-mb-5" style="margin-bottom:20px;">
                        <div class="col tx-center" style="text-align:center;">
                            <h1 style="font-size:18px;">RSPAD GATOT SOEBROTO <br> INSTALASI FARMASI</h1>
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
        $pdf->loadHTML($html)->setPaper('a5', 'portrait');
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
        $pdf->loadHTML($html)->setPaper('a5', 'portrait');
        return $pdf->stream();
    }

    public function radiologi()
    {
        $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
        $html = '<div style="font-size: 12px;">
            <div class="col-7 bd p-p-3 bg-white rounded">
                    <div class="row p-mb-5" style="margin-bottom:20px;">
                        <div class="col tx-center" style="text-align:center;">
                            <h1 style="font-size:18px;">RSPAD GATOT SOEBROTO <br> RADIOLOGI</h1>
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
        $pdf->loadHTML($html)->setPaper('a5', 'portrait');
        return $pdf->stream();
    }
}
