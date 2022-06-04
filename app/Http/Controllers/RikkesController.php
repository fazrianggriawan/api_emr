<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\PDFBarcode;

class RikkesController extends BaseController
{
    public function GetDataPeserta()
    {
        $peserta = DB::table('rikkes_peserta')->get();
        return LibApp::response_success(@$peserta);
    }

    public function GetPesertaByNoUrut($noUrut)
    {
        $peserta = DB::table('rikkes_peserta')->where('noUrut', $noUrut)->get();
        return LibApp::response_success(@$peserta[0]);
    }

    public function GetDataRikkes($idPeserta)
    {
        $rikkes = DB::table('rikkes_hasil_1')
                ->leftJoin('rikkes_hasil_2', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_2.id_rikkes_peserta' )
                ->leftJoin('rikkes_hasil_3', 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_3.id_rikkes_peserta' )
                ->orderBy('rikkes_hasil_1.dateCreated', 'desc')
                ->where('rikkes_hasil_1.id_rikkes_peserta', $idPeserta)->get();

        $odontogram = DB::table('rikkes_hasil_odontogram')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)->get();

        $res = array('rikkes'=>@$rikkes[0], 'odontogram'=>$odontogram);

        return LibApp::response_success($res);
    }

    public function debug($idPeserta)
    {
        $rikkes = DB::table(DB::raw('(SELECT * from rikkes_hasil_1 WHERE id_rikkes_peserta = '.$idPeserta.' ORDER BY dateCreated LIMIT 1) as rikkes_hasil_1'))
                    ->leftJoin(DB::raw('(SELECT * from rikkes_hasil_2 WHERE id_rikkes_peserta = '.$idPeserta.' ORDER BY dateCreated LIMIT 1) as rikkes_hasil_2'), 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_2.id_rikkes_peserta' )
                    ->leftJoin(DB::raw('(SELECT * from rikkes_hasil_3 WHERE id_rikkes_peserta = '.$idPeserta.' ORDER BY dateCreated LIMIT 1) as rikkes_hasil_3'), 'rikkes_hasil_1.id_rikkes_peserta', '=', 'rikkes_hasil_3.id_rikkes_peserta' )
                    ->get();

        $odontogram = DB::table('rikkes_hasil_odontogram')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)->get();

        $res = array('rikkes'=>@$rikkes[0], 'odontogram'=>$odontogram);

        return LibApp::response_success($res);
    }

    public function Save(Request $request)
    {
        $data = array(
            'id_rikkes_peserta' => $request->input('peserta')['id'],
            'anamnesa' => $request->input('anamnesa'),
            'tinggi' => $request->input('tinggi'),
            'berat' => $request->input('berat'),
            'imt' => $request->input('imt'),
            'tekananDarah' => $request->input('tekananDarah'),
            'nadi' => $request->input('nadi'),
            'tubuhBentuk' => $request->input('tubuhBentuk'),
            'tubuhGerak' => $request->input('tubuhGerak'),
            'kepala' => $request->input('kepala'),
            'muka' => $request->input('muka'),
            'leher' => $request->input('leher'),
            'mata' => $request->input('mata'),
            'od1' => $request->input('od1'),
            'od2' => $request->input('od2'),
            'od3' => $request->input('od3'),
            'os1' => $request->input('os1'),
            'os2' => $request->input('os2'),
            'os3' => $request->input('os3'),
            'campus' => $request->input('campus'),
            'kenalWarna' => $request->input('kenalWarna'),
            'lainLain' => $request->input('lainLain'),
            'dateCreated' => date('Y-m-d h:i:s'),
        );

        $data2 = array(
            'id_rikkes_peserta' => $request->input('peserta')['id'],
            'telinga' => $request->input('telinga'),
            'ad' => $request->input('ad'),
            'as' => $request->input('as'),
            'tajamPend' => $request->input('tajamPend'),
            'membranTymp' => $request->input('membranTymp'),
            'penyTel' => $request->input('penyTel'),
            'hidung' => $request->input('hidung'),
            'tenggorokan' => $request->input('tenggorokan'),
            'gigiMulut' => $request->input('gigiMulut'),
            'gigiD' => $request->input('gigiD'),
            'gigiM' => $request->input('gigiM'),
            'gigiF' => $request->input('gigiF'),
            'karang' => $request->input('karang'),
            'protesa' => $request->input('protesa'),
            'penyMulut' => $request->input('penyMulut'),
            'thoraxPernafasan' => $request->input('thoraxPernafasan'),
            'thoraxBentuk' => $request->input('thoraxBentuk'),
            'cor' => $request->input('cor'),
            'pulmo' => $request->input('pulmo'),
            'abdomen' => $request->input('abdomen'),
            'lien' => $request->input('lien'),
            'hepar' => $request->input('hepar'),
            'regioInguinalis' => $request->input('regioInguinalis'),
            'genitalia' => $request->input('genitalia'),
            'perineum' => $request->input('perineum'),
            'angGerakAtas' => $request->input('angGerakAtas'),
            'angGerakBawah' => $request->input('angGerakBawah'),
            'kulit' => $request->input('kulit'),
            'refleks' => $request->input('refleks'),
            'dateCreated' => date('Y-m-d h:i:s'),
        );

        $data3 = array(
            'id_rikkes_peserta' => $request->input('peserta')['id'],
            'hasilLab' => $request->input('hasilLab'),
            'hasilEkg' => $request->input('hasilEkg'),
            'hasilRadiologi' => $request->input('hasilRadiologi'),
            'hasilAudiometri' => $request->input('hasilAudiometri'),
            'hasilKeswaKode' => $request->input('hasilKeswaKode'),
            'hasilKeswaKeterangan' => $request->input('hasilKeswaKeterangan'),
            'kesimpulanPemeriksaan' => $request->input('kesimpulanPemeriksaan'),
            'A' => $request->input('A'),
            'B' => $request->input('B'),
            'D' => $request->input('D'),
            'G' => $request->input('G'),
            'J' => $request->input('J'),
            'L' => $request->input('L'),
            'U' => $request->input('U'),
            'stakes' => $request->input('stakes'),
            'hasil' => $request->input('hasil'),
            'dateCreated' => date('Y-m-d h:i:s'),
        );

        DB::beginTransaction();

        DB::table('rikkes_hasil_odontogram')->where('id_rikkes_peserta', $request->input('peserta')['id'])->update(array('active'=>0));

        foreach ($request->input('odontogram') as $key => $value) {
            $odontogram = array(
                'keterangan' => $value['keterangan'],
                'atas' => $value['atas'],
                'bawah' => $value['bawah'],
                'posisi' => $value['posisi'],
                'id_rikkes_peserta' => $request->input('peserta')['id'],
                'dateCreated' => date('Y-m-d h:i:s'),
            );
            DB::table('rikkes_hasil_odontogram')->insert($odontogram);
        }

        if( !$request->input('id') ){
            $hasil = DB::table('rikkes_hasil_1')->insert($data);
            $hasil = DB::table('rikkes_hasil_2')->insert($data2);
            $hasil = DB::table('rikkes_hasil_3')->insert($data3);
        }else{
            $hasil = DB::table('rikkes_hasil_1')->where('id', $request->input('peserta')['id'])->update($data);
            $hasil = DB::table('rikkes_hasil_2')->where('id', $request->input('peserta')['id'])->update($data2);
            $hasil = DB::table('rikkes_hasil_3')->where('id', $request->input('peserta')['id'])->update($data3);
        }

        DB::commit();

        return LibApp::response_success($hasil);

    }

    public function PrintSticker($noUrut)
    {
        header("Content-type:application/pdf");

        $peserta = DB::table('rikkes_peserta')->where('noUrut', $noUrut)->get();
        $arrPeserta = DB::table('rikkes_peserta')->get();

		$border = 0;
		$heightCell = 3;
		$widthCell = 57;
		$fontWeight = '';

		$fontBody = 9;
		$marginLeft = 3;
		$fontWeight = '';

		$pdf = new PDFBarcode();

		$pdf->AddPage('L', [60,30], 0);
		$pdf->SetAutoPageBreak(false);
		$pdf->SetLeftMargin($marginLeft);
		$pdf->SetTopMargin(0);

		$pdf->SetFont('arial', $fontWeight, $fontBody);
		$pdf->SetY(1);
		$pdf->Cell($widthCell, $heightCell+2, strtoupper($peserta[0]->nama), $border);
		$pdf->ln();
        $heightCell++;
		$pdf->Cell($widthCell, $heightCell, 'No.Peserta : '.$peserta[0]->noUrut.' - '.$peserta[0]->noPeserta, $border);
		$pdf->ln();
		$pdf->Cell($widthCell, $heightCell, 'Tgl.Lahir : '.$peserta[0]->tglLahir.' ('.strtoupper($peserta[0]->jnsKelamin).')', $border);
		$pdf->SetFont('arial', '', $fontBody);
		$pdf->Code128( $marginLeft+1.3, 15, $peserta[0]->noPeserta, 40, 9); // Barcode
		$pdf->SetFont('arial', $fontWeight, $fontBody);

		$pdf->Output();
        exit;
    }

    public function PrintStickerAllPeserta($noUrutFrom, $noUrutTo)
    {
        header("Content-type:application/pdf");

        $arrPeserta = DB::table('rikkes_peserta')->where('noUrut','>=',$noUrutFrom)->where('noUrut','<=',$noUrutTo)->get();

		$pdf = new PDFBarcode();

        foreach ($arrPeserta as $key => $value) {
            $border = 0;
            $heightCell = 3;
            $widthCell = 57;
            $fontWeight = '';

            $fontBody = 9;
            $marginLeft = 3;
            $fontWeight = '';

            $pdf->AddPage('L', [60,30], 0);
            $pdf->SetAutoPageBreak(false);
            $pdf->SetLeftMargin($marginLeft);
            $pdf->SetTopMargin(0);

            $pdf->SetFont('arial', $fontWeight, $fontBody);
            $pdf->SetY(1);
            $pdf->Cell($widthCell, $heightCell+2, strtoupper($value->nama), $border);
            $pdf->ln();
            $heightCell++;
            $pdf->Cell($widthCell, $heightCell, 'No.Peserta : '.$value->noUrut.' - '.$value->noPeserta, $border);
            $pdf->ln();
            $pdf->Cell($widthCell, $heightCell, 'Tgl.Lahir : '.$value->tglLahir.' ('.strtoupper($value->jnsKelamin).')', $border);
            $pdf->SetFont('arial', '', $fontBody);
            $pdf->Code128( $marginLeft+1.3, 15, $value->noPeserta, 40, 9); // Barcode
            $pdf->SetFont('arial', $fontWeight, $fontBody);
        }

		$pdf->Output();
        exit;
    }

    public function SaveHasilRadiologi(Request $request) {
        DB::beginTransaction();

        DB::table('rikkes_hasil_radiologi')->where('id_rikkes_peserta', $request->idPeserta)->update(array('active'=>0));

        $data = array(
            'id_rikkes_peserta' => $request->idPeserta,
            'keterangan' => $request->hasil,
            'dokter' => $request->dokter,
            'dateCreated' => date("Y-m-d h:i:s")
        );
        $insert = DB::table('rikkes_hasil_radiologi')->insert($data);
        DB::commit();

        return LibApp::response_success($insert);
    }

    public function GetHasilRadiologi($idPeserta)
    {
        $data = DB::table('rikkes_hasil_radiologi')
                ->where('id_rikkes_peserta', $idPeserta)
                ->where('active', 1)
                ->get();
        return LibApp::response_success(@$data[0]);
    }

    public function SaveHasilLab(Request $request) {
        DB::beginTransaction();

        DB::table('rikkes_hasil_lab')->where('id_rikkes_peserta', $request->idPeserta)->update(array('active'=>0));
        DB::table('rikkes_hasil_lab_keterangan')->where('id_rikkes_peserta', $request->idPeserta)->update(array('active'=>0));

        $data = array();
        foreach ($request->data as $key => $value) {
            $data[$key] = $value;
            $data[$key]['dateCreated'] = date('Y-m-d h:i:s');
            $data[$key]['id_rikkes_peserta'] = $request->idPeserta;
        }

        $keteranganHasil = $request->keterangan;
        $keterangan = array(
                'id_rikkes_peserta'=>$request->idPeserta,
                'catatan'=>$keteranganHasil['catatan'],
                'pemeriksa'=>$keteranganHasil['pemeriksa'],
                'dateCreated'=>date('Y-m-d h:i:s'),
            );
        $insert = DB::table('rikkes_hasil_lab_keterangan')->insert($keterangan);

        $insert = DB::table('rikkes_hasil_lab')->insert($data);

        DB::commit();

        return LibApp::response_success($insert);
    }

    public function GetHasilLab($idPeserta)
    {
        $data['hasil'] = DB::table('rikkes_hasil_lab')
                    ->select('name','hasil','nilaiRujukan','group')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)
                    ->get();

        $data['keterangan'] = DB::table('rikkes_hasil_lab_keterangan')
                    ->select('catatan','pemeriksa')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)
                    ->get();

        return LibApp::response_success($data);
    }

    public function GetHasilLabKeterangan($idPeserta)
    {
        $data = DB::table('rikkes_hasil_lab_keterangan')
                ->select('catatan','pemeriksa')
                ->where('id_rikkes_peserta', $idPeserta)
                ->where('active', 1)
                ->get();
        return LibApp::response_success($data);
    }

    public function PrintHasilLab($idPeserta)
    {
        $peserta = DB::table('rikkes_peserta')->where('id', $idPeserta)->get();
        $hasilLab = DB::table('rikkes_hasil_lab')
                    ->select('name','hasil','nilaiRujukan','group','dateCreated')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('hasil', '<>', '')
                    ->where('active', 1)
                    ->get();

        $keterangan = DB::table('rikkes_hasil_lab_keterangan')
                    ->select('catatan','pemeriksa')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)
                    ->get();

        if( count($hasilLab) == 0 ){
            echo 'Data Belum Terinput';
            exit;
        }

        $data = collect($hasilLab)->groupBy('group');

        $border = 0;
        $heightCell = 2;
        $widthCell = 57;
        $fontWeight = '';

        $fontBody = 9;
        $marginLeft = 3;
        $fontWeight = '';

        $pdf = new PDFBarcode();

        $pdf->AddPage('P');
        $pdf->SetAutoPageBreak(false);

        $pdf->SetFont('arial', $fontWeight, $fontBody);

        $pdf->setY(5);
        $pdf->Cell($widthCell+20, $heightCell+2, 'DENKESYAH 030401 BOGOR', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'PEMERIKSAAN KESEHATAN', $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'RUMAH SAKIT TK III 030702 SALAK', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'JAM/TGL : '.date("h:i:s d-m-Y", strtotime($hasilLab[0]->dateCreated)), $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'JL JENDERAL SUDIRMAN NO 8 - BOGOR', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'NO. PESERTA : '.$peserta[0]->noPeserta, $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, '', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'NO. URUT : '.$peserta[0]->noUrut, $border);
        $pdf->ln();

        $pdf->Cell($widthCell, $heightCell+10, '', $border);
        $pdf->Cell($widthCell+60, $heightCell+10, 'HASIL PEMERIKSAAN LABORATORIUM', $border);

        $pdf->ln();
        $pdf->Cell($widthCell+60, $heightCell, 'Nama Peserta : '.strtoupper($peserta[0]->nama), $border);
        $pdf->Cell($widthCell, $heightCell, 'Jenis Kelamin : '.strtoupper($peserta[0]->jnsKelamin), $border);
        $pdf->ln(7);

        $pdf->Cell($widthCell+4, $heightCell+5, 'PEMERIKSAAN', 'T');
        $pdf->Cell($widthCell, $heightCell+5, 'HASIL PEMERIKSAAN', 'T');
        $pdf->Cell($widthCell, $heightCell+5, 'NILAI RUJUKAN', 'T');
        $pdf->ln();

        $border = 1;
        foreach ($data as $key => $value) {
            $pdf->SetFont('Arial','B', $fontBody);
            $pdf->Cell(($widthCell*3)+5, $heightCell+2, strtoupper($key), '');
            $pdf->ln();
            foreach ($value as $key2 => $value2) {
                $pdf->SetFont('Arial','', $fontBody);
                $pdf->Cell(5, $heightCell+2, '', '');
                $pdf->Cell($widthCell, $heightCell+2, strtoupper($value2->name), 'B');
                $pdf->Cell($widthCell, $heightCell+2, $value2->hasil, 'B');
                $pdf->Cell($widthCell, $heightCell+2, $value2->nilaiRujukan, 'B');
                $pdf->ln();
            }
            $pdf->ln(3);
        }
        $pdf->ln(3);
        $pdf->Cell($widthCell+62, $heightCell+5, 'CATATAN :', '');
        $pdf->Cell($widthCell, $heightCell+5, 'PEMERIKSA', '');
        $pdf->ln(20);
        $pdf->Cell($widthCell+62, $heightCell+5, '', '');
        $pdf->Cell($widthCell, $heightCell+5, strtoupper($keterangan[0]->pemeriksa), '');
        $pdf->ln();
        $x = $pdf->GetX();
        $y = $pdf->GetY();
        $pdf->SetXY($x, $y-20);
        $pdf->MultiCell($widthCell+40, $heightCell+1, $keterangan[0]->catatan, '');
        $pdf->Output();
        exit;

    }

    public function PrintHasilRadiologi($idPeserta)
    {
        $peserta = DB::table('rikkes_peserta')->where('id', $idPeserta)->get();
        $hasil = DB::table('rikkes_hasil_radiologi')
                    ->where('id_rikkes_peserta', $idPeserta)
                    ->where('active', 1)
                    ->get();

        if( count($hasil) == 0 ){
            echo 'Data Belum Terinput';
            exit;
        }

        $border = 0;
        $heightCell = 2;
        $widthCell = 57;
        $fontWeight = '';

        $fontBody = 9;
        $marginLeft = 3;
        $fontWeight = '';

        $pdf = new PDFBarcode();

        $pdf->AddPage('P');
        $pdf->SetAutoPageBreak(false);

        $pdf->SetFont('arial', $fontWeight, $fontBody);

        $pdf->setY(5);
        $pdf->Cell($widthCell+20, $heightCell+2, 'DENKESYAH 030401 BOGOR', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'PEMERIKSAAN KESEHATAN', $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'RUMAH SAKIT TK III 030702 SALAK', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'JAM/TGL : '.date("h:i:s d-m-Y", strtotime($hasil[0]->dateCreated)), $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, 'JL JENDERAL SUDIRMAN NO 8 - BOGOR', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'NO. PESERTA : '.$peserta[0]->noPeserta, $border);
        $pdf->ln();
        $pdf->Cell($widthCell+20, $heightCell+2, '', $border);
        $pdf->Cell($widthCell, $heightCell+2, 'NO. URUT : '.$peserta[0]->noUrut, $border);
        $pdf->ln();

        $pdf->Cell($widthCell, $heightCell+10, '', $border);
        $pdf->Cell($widthCell+60, $heightCell+10, 'HASIL PEMERIKSAAN RADIOLOGI', $border);

        $pdf->ln();
        $pdf->Cell($widthCell+60, $heightCell+3, 'Nama Peserta : '.strtoupper($peserta[0]->nama), $border);
        $pdf->Cell($widthCell, $heightCell+3, 'Jenis Kelamin : '.strtoupper($peserta[0]->jnsKelamin), $border);
        $pdf->ln(7);

        $pdf->Cell($widthCell, $heightCell+5, 'HASIL PEMERIKSAAN', 'B');
        $pdf->ln(8);
        $pdf->Cell($widthCell, $heightCell+3, 'Ts. Yth :', $border);
        $pdf->ln(2);
        $pdf->SetLeftMargin(20);
        $pdf->WriteHTML($hasil[0]->keterangan);
        $pdf->SetLeftMargin(10);
        $pdf->ln();
        $pdf->Cell($widthCell+100, $heightCell+5, '', 'B');

        $pdf->ln(8);
        $pdf->Cell($widthCell+60, $heightCell+5, 'Terima kasi atas kepercayaan sejawat', $border);
        $pdf->Cell($widthCell, $heightCell+5, 'Dokter Pemeriksa,', $border);
        $pdf->ln(20);
        $pdf->Cell($widthCell+60, $heightCell+5, '', $border);
        $pdf->Cell($widthCell, $heightCell+5, $hasil[0]->dokter, $border);
        $pdf->Output();
        exit;

    }
}
