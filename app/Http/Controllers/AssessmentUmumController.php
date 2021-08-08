<?php
namespace App\Http\Controllers;

use App\Models\AssessmentUmum;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class AssessmentUmumController extends Controller{
    public function save(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $mod = new AssessmentUmum();
        dd($request);

        $mod->noreg = $request->noreg;
//        $mod->keluhan_utama_id = $request->;
//        $mod->keluhan_utama_name = $request->;
//        $mod->keluhan_tambahan_id = $request->;
//        $mod->keluhan_tambahan_name = $request->;
//        $mod->keluhan_utama_sejak = $request->;
//        $mod->keluhan_tambahan_sejak = $request->;
        $mod->bb = $request->bb;
        $mod->tb = $request->tb;
        $mod->td = $request->td;
        $mod->nadi = $request->nadi;
        $mod->p = $request->p;
        $mod->suhu = $request->suhu;


        $data = array(
            'tgl_reg_from' => $tanggal,
            'tgl_reg_to' => $tanggal,
            'id_ruangan' => "034"
        );

        if( $request->input('id_pasien') ){
            $data['id_pasien'] = $request->input('id_pasien');
            $data['tgl_reg_from'] = '2015-01-01';
            $data['tgl_reg_to'] = '2021-12-31';
        }

        $endpoint = setEndpoint('/api/registrasi/pencarian');
        $client = new Client();

        $req = $client->request('POST', $endpoint, ['headers'=>getHeaderEndPoint(), 'body'=>json_encode($data)]);
        $json = json_decode($req->getBody()->getContents());

        if( $json->metadata->status == 200 ){
            if( isset($json->response) ){
                return json_encode(array('status'=>200, 'data'=>$json->response->data, 'message'=>''));
            }else{
                return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
            }
        }else{
            return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
        }
    }

}
