<?php

namespace App\Http\Controllers\Master;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use phpDocumentor\Reflection\PseudoTypes\LowercaseString;

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
        $data = DB::table('mst_grouppas')
                ->where('active', 1)
                ->orderBy('increment')
                ->get();
        return LibApp::response_success($data);
    }

    public function GolonganPasien($idGroupPasien)
    {
        $data = DB::table('mst_golpas')
                ->select('id', 'name')
                ->where('status', 1)
                ->where('group', $idGroupPasien)
                ->get();
        return LibApp::response_success($data);
    }

    public function Poliklinik()
    {
        $data = DB::table('mst_poli')
                ->select('mst_ruangan.id', 'mst_ruangan.name', 'mst_poli.*')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'mst_poli.id_ruangan')
                ->where('mst_ruangan.active', 1)->get();
        return LibApp::response_success($data);
    }

    public function Keluhan()
    {
        $data = DB::table('keluhan')->get();
        return LibApp::response_success($data);
    }

    public function Dokter()
    {
        $data = DB::table('mst_pelaksana')
                ->select('id','name')
                ->where('group', 'dokter')->get();
        return LibApp::response_success($data);
    }

    public function DokterByPoli($idRuangan)
    {
        $data = DB::table('mst_pelaksana_poli')
                ->select('mst_pelaksana.id','mst_pelaksana.name')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'mst_pelaksana_poli.id_pelaksana')
                ->where('mst_pelaksana_poli.id_ruangan', $idRuangan)
                ->where('mst_pelaksana.group', 'dokter')->get();
        return LibApp::response_success($data);
    }

    public function JenisPerawatan()
    {
        $data = DB::table('mst_jns_perawatan')
                ->where('active', 1)->get();
        return LibApp::response_success($data);
    }

    public function WaktuPelayanan()
    {
        $data = DB::table('mst_waktu_pelayanan')
                ->where('active', 1)->get();
        return LibApp::response_success($data);
    }

    public function Ruangan($jnsPerawatan)
    {
        $data = DB::table('mst_ruangan')
                ->where('jns_perawatan', strtolower($jnsPerawatan))
                ->where('active', 1)->get();
        return LibApp::response_success($data);
    }

    public function KelasRuangan()
    {
        $data = DB::table('mst_kelas')
                ->select('id', 'name')
                ->where('active', 1)->get();
        return LibApp::response_success($data);
    }

    public function TempatTidurByRuangan($idRuangan)
    {
        $data = DB::table('mst_ruangan_bed')
                ->select('mst_ruangan.name as ruangan','mst_ruangan_bed.kamar', 'mst_ruangan_bed.no_bed','mst_kelas.name as kelas')
                ->leftJoin('mst_kelas_ruangan', 'mst_kelas_ruangan.id', '=', 'mst_ruangan_bed.id_kelas_ruangan')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'mst_kelas_ruangan.id_ruangan')
                ->leftJoin('mst_kelas', 'mst_kelas.id', '=', 'mst_kelas_ruangan.id_kelas')
                ->where('mst_ruangan.id', $idRuangan)
                ->get();
        return LibApp::response_success($data);
    }
}
