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

$router->get('master_obat', 'FarmasiController@getMasterObat');
$router->get('master_dokter', 'DokterController@getAllDokter');
$router->get('master_dokter_by_poli', 'DokterController@getAllDokterByPoli');
$router->get('master_lab', 'LabController@getAllMaster');
$router->get('master_lab_cito', 'LabController@getAllMasterCito');
$router->get('master_rad', 'RadiologiController@getAllMaster');
$router->get('master_rad_detail', 'RadiologiController@getAllMasterDetail');
$router->get('master_pat_anatomi', 'PatAnatomiController@getAllMaster');
$router->get('master_poli', 'MasterController@poliklinik');
$router->get('icd9', 'IcdController@Icd9');
$router->get('sig_template', 'FarmasiController@getSigTemplate');
$router->get('print_farmasi', 'PrintController@farmasi');
$router->get('print_lab', 'PrintController@laboratorium');
$router->get('print_rad', 'PrintController@radiologi');
$router->get('print_tcpdf', 'PrintController@tcpdf');
$router->get('get_test_order', 'TestOrderController@getData');
$router->get('get_objective', 'ObjectiveController@getData');
$router->get('get_assessment_umum', 'AssessmentUmumController@getData');

$router->post('registrasi', 'RegistrasiController@getDataRegistrasi');
$router->post('cppt', 'CpptController@save');
$router->post('do_login', 'LoginController@doLogin');
$router->post('aa', 'LoginController@aa');
$router->post('upload', 'UploadController@doUpload');
$router->post('save_sig_template', 'FarmasiController@saveSigTemplate');
$router->post('save_diagnosa', 'DiagnosaController@save');
$router->post('save_tindakan', 'TindakanController@save');
$router->post('save_test_order', 'TestOrderController@save');
$router->post('save_assessment_umum', 'AssessmentUmumController@save');
$router->post('save_objective', 'ObjectiveController@save');
