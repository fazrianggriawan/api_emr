<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class MasterController extends BaseController
{
    public function poliklinik(Request $request)
    {
        $endpoint = setEndpoint('/api/master/poli/all');
        $client = new Client();

        $req = $client->request('GET', $endpoint, ['headers'=>getHeaderEndPoint()]);
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
