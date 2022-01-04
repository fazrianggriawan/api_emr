<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SalakController extends Controller{

    public function getRegistrasi()
    {
        $endpoint = 'http://182.253.22.220/online/apix/getaji.php?namadata=zbilling_kasir_jalan&key=tglmasuk&value=2013-05-16';
        $client = new Client();

        $req = $client->request('GET', $endpoint);
        $json = json_decode($req->getBody()->getContents());
        dd($json);
    }

}
