<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;


class RegistrasiController extends Controller{
    public function getDataRegistrasi(Request $request)
    {
        $tanggal = $request->input('tanggal');

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
