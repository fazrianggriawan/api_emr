<?php

namespace App\Http\Controllers;

use App\Models\Farmasi;
use App\Models\SigTemplate;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class FarmasiController extends BaseController
{
    public function getMasterObat()
    {
        $endpoint = setEndpoint('/api/master/farmasi/stok/gudang');
        $data = array(
            "fornas" => "1",
            "nama_obat" => "",
        );
        $client = new Client();
        $req = $client->request('POST', $endpoint, ['headers'=>getHeaderEndPoint(), 'body'=>json_encode($data)]);
        $json = json_decode($req->getBody()->getContents());


        if( $json->metadata->status == 200 ){
            return json_encode($json->response->data);
        }else{
            return '';
        }
    }

    public function saveSigTemplate(Request $request)
    {
        $mod = new SigTemplate();
        $mod->value = $request->sigText;
        $mod->save();
    }

    public function getSigTemplate(Request $request)
    {
        $mod = new SigTemplate();
        $data = $mod->getAllData();
        return json_encode($data);
    }

    public function saveObat(Request $request)
    {
        $mod = new Farmasi();
        $mod->obat_jns_fornas = $request->jns_fornas;
        $mod->obat_nama_obat = $request->nama_obat;
        $mod->obat_satuan = $request->satuan;
        $mod->obat_sok_gudang = $request->stok_gudang;
        $mod->jumlah_obat = $request->jumlahObat;
        $mod->dosis = $request->dosis;
        $mod->unit = $request->unit;
        $mod->durasi = $request->durasi;
        $mod->frekuensi = $request->frekuensi;
        $mod->route = $request->route;
        $mod->arahan = $request->arahan;

        $mod->save();

    }

}
