<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use GuzzleHttp\Client;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use App\Models\Keluhan;
use App\Models\Icd10;


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('master_poli', function(){
    $endpoint = setEndpoint('/api/master/poli/all');
    $client = new GuzzleHttp\Client();
    $req = $client->request('GET', $endpoint, ['headers'=>getHeaderEndPoint()]);
    $json = json_decode($req->getBody()->getContents());

    if( $json->metadata->status == 200 ){
        return $json->response->data;
    }else{
        return $json->metadata->message;
    }
});

$router->post('registrasi', function(Request $request){
    $tanggal = $request->input('tanggal');

    $data = array(
        "tgl_reg_from" => $tanggal,
        "tgl_reg_to" => $tanggal,
        "id_ruangan" => "034"
    );

    $endpoint = setEndpoint('/api/registrasi/pencarian');
    $client = new GuzzleHttp\Client();

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
});

$router->put('registrasi', function (Request $request){
    $data = array(
        'noreg' => $request->input('noreg'),
        'dpjp' => $request->input('dpjp')
    );
    $client = new GuzzleHttp\Client();
    $req = $client->request('PUT', setEndpoint('/api/registrasi/'), ['headers'=>getHeaderEndPoint(), 'body'=>json_encode($data)]);
    $json = json_decode($req->getBody()->getContents());

    if( $json->metadata->status == 200 ){
        return $json->response->data;
    }else{
        return $json->metadata->message;
    }
});

$router->get('tpl_keluhan', function(){
    return json_encode(Keluhan::all()->where('active', 1)->all());
});

$router->get('icd10', function(){
    $icd10 = new Icd10();
    return json_encode($icd10->getIcd10());
});

//$router->get('master_dokter', function(){
//    $dokter = new \App\Models\Dokter();
//    return json_encode($dokter->getAll());
//});

$router->post('cppt', 'CpptController@save');
$router->get('master_obat', 'FarmasiController@getMasterObat');
$router->get('master_dokter', 'DokterController@getAllDokter');
$router->get('master_lab', 'LabController@getAllMaster');
$router->get('master_lab_cito', 'LabController@getAllMasterCito');
$router->get('master_rad', 'RadiologiController@getAllMaster');
$router->post('login', 'LoginController@doLogin');
$router->post('aa', 'LoginController@aa');
$router->get('icd9', 'IcdController@Icd9');

