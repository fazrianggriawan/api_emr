<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use App\Models\Keluhan;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class MasterController extends BaseController
{

    public function Rs()
    {
        $data = DB::table('mst_rs')->get();
        return LibApp::response_success($data);
    }

    public function AwalanNama()
    {
        $data = DB::table('mst_awalan')->get();
        return LibApp::response_success($data);
    }

    public function Negara()
    {
        $data = DB::table('mst_negara')->get();
        return LibApp::response_success($data);
    }

    public function Provinsi()
    {
        $data = DB::table('mst_provinsi')->get();
        return LibApp::response_success($data);
    }

    public function Kota($idProvinsi)
    {
        $data = DB::table('mst_kota')->where('id_provinsi', $idProvinsi)->get();
        return LibApp::response_success($data);
    }

    public function Kecamatan($idKota)
    {
        $data = DB::table('mst_kecamatan')->where('id_kota', $idKota)->get();
        return LibApp::response_success($data);
    }

    public function Kelurahan($idKecamatan)
    {
        $data = DB::table('mst_kelurahan')->where('id_kecamatan', $idKecamatan)->get();
        return LibApp::response_success($data);
    }

    public function Suku()
    {
        $data = DB::table('mst_suku')->get();
        return LibApp::response_success($data);
    }

    public function StatusNikah()
    {
        $data = DB::table('mst_status_nikah')->get();
        return LibApp::response_success($data);
    }

    public function Agama()
    {
        $data = DB::table('mst_agama')->get();
        return LibApp::response_success($data);
    }

    public function Pekerjaan()
    {
        $data = DB::table('mst_pekerjaan')->get();
        return LibApp::response_success($data);
    }

    public function Pendidikan()
    {
        $data = DB::table('mst_pendidikan')->get();
        return LibApp::response_success($data);
    }

    public function Angkatan()
    {
        $data = DB::table('mst_angkatan')->get();
        return LibApp::response_success($data);
    }

    public function Pangkat()
    {
        $data = DB::table('mst_pangkat')->get();
        return LibApp::response_success($data);
    }

    public function GroupPasien()
    {
        $data = DB::table('mst_grouppas')->orderBy('increment')->get();
        return LibApp::response_success($data);
    }

    public function GolonganPasien($idGroupPasien)
    {
        $data = DB::table('mst_golpas')->where('status', 1)->where('group', $idGroupPasien)->get();
        return LibApp::response_success($data);
    }

    public function poliklinik(Request $request)
    {
        // $data = DB::table('mst_golpas')->where('status', 1)->where('group', $idGroupPasien)->get();
        // return LibApp::response_success($data);
    }

    public function keluhan()
    {
        $mod = new Keluhan();
        return $mod->getTpl();
    }
}
