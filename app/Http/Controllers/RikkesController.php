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
        $data = DB::table('rikkes_hasil')->where('id_rikkes_peserta', $idPeserta)->get();
        return LibApp::response_success(@$data[0]);
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

        if( !$request->input('id') ){
            $hasil = DB::table('rikkes_hasil')->insert($data);
            return LibApp::response_success($hasil);
        }else{
            $hasil = DB::table('rikkes_hasil')->where('id', $request->input('id'))->update($data);
            return LibApp::response_success($hasil);
        }
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
}
