<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

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

    public function Save(Request $request)
    {
        $data = array(
            'id_rikkes_peserta' => $request->input('peserta')['id'],
            'abdomen' => $request->input('abdomen'),
            'ad' => $request->input('ad'),
            'angGerakAtas' => $request->input('angGerakAtas'),
            'angGerakBawah' => $request->input('angGerakBawah'),
            'as' => $request->input('as'),
            'berat' => $request->input('berat'),
            'campus' => $request->input('campus'),
            'cor' => $request->input('cor'),
            'd' => $request->input('d'),
            'f' => $request->input('f'),
            'genitalia' => $request->input('genitalia'),
            'hasil' => $request->input('hasil')['name'],
            'hepar' => $request->input('hepar'),
            'hidung' => $request->input('hidung'),
            'hymen' => $request->input('hymen'),
            'karang' => $request->input('karang'),
            'kenalWarna' => $request->input('kenalWarna'),
            'kepala' => $request->input('kepala'),
            'kesimpulan' => $request->input('kesimpulan'),
            'kulit' => $request->input('kulit'),
            'lainLain' => $request->input('lainLain'),
            'leher' => $request->input('leher'),
            'lien' => $request->input('lien'),
            'm' => $request->input('m'),
            'membranTymp' => $request->input('membranTymp'),
            'muka' => $request->input('muka'),
            'nadi' => $request->input('nadi'),
            'odLensaKoreksi' => $request->input('odLensaKoreksi'),
            'odVisusAwal' => $request->input('odVisusAwal'),
            'odVisusKoreksi' => $request->input('odVisusKoreksi'),
            'osLensaKoreksi' => $request->input('osLensaKoreksi'),
            'osVisusAwal' => $request->input('osVisusAwal'),
            'osVisusKoreksi' => $request->input('osVisusKoreksi'),
            'palpasi' => $request->input('palpasi'),
            'penyMulut' => $request->input('penyMulut'),
            'penyTel' => $request->input('penyTel'),
            'perineum' => $request->input('perineum'),
            'protesa' => $request->input('protesa'),
            'pulmo' => $request->input('pulmo'),
            'refleks' => $request->input('refleks'),
            'regioInguinalis' => $request->input('regioInguinalis'),
            'rumusLahirA' => $request->input('rumusLahirA'),
            'rumusLahirB' => $request->input('rumusLahirB'),
            'rumusLahirD' => $request->input('rumusLahirD'),
            'rumusLahirG' => $request->input('rumusLahirG'),
            'rumusLahirJ' => $request->input('rumusLahirJ'),
            'rumusLahirL' => $request->input('rumusLahirL'),
            'rumusLahirU' => $request->input('rumusLahirU'),
            'stakes' => $request->input('stakes'),
            'tajamPend' => $request->input('tajamPend'),
            'tekananDarah' => $request->input('tekananDarah'),
            'tenggorokan' => $request->input('tenggorokan'),
            'thoraxBentuk' => $request->input('thoraxBentuk'),
            'thoraxPernafasan' => $request->input('thoraxPernafasan'),
            'tinggi' => $request->input('tinggi'),
            'tubuhBentuk' => $request->input('tubuhBentuk'),
            'tubuhGerak' => $request->input('tubuhGerak'),
            'dateCreated' => date('Y-m-d h:i:s'),
        );

        $hasil = DB::table('rikkes_hasil')->insert($data);

        return LibApp::response_success($hasil);
    }
}
