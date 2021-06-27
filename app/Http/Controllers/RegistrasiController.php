<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;


class RegistrasiController extends Controller{
    public function allDataRegistrasi(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $date = explode($tanggal);

        $endpoint = setEndpoint('/api/registrasi/pencarian');
        $data = array(
            "tgl_reg_from" => "2021-06-17",
            "tgl_reg_to" => "2021-06-17",
            "id_ruangan" => "034"
        );
        $response = Http::withHeaders(getHeaderEndPoint())->post($endpoint, $data)->body();
        $json = json_decode($response);

        if( $json->metadata->status == 200 ){
            return $json->response->data;
        }else{
            return '';
        }
    }

}
