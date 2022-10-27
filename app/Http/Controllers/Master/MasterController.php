<?php

namespace App\Http\Controllers\Master;

use App\Http\Libraries\LibApp;
use App\Models\Mst_agama;
use App\Models\Mst_angkatan;
use App\Models\Mst_awalan;
use App\Models\Mst_golpas;
use App\Models\Mst_grouppas;
use App\Models\Mst_jns_bayar;
use App\Models\Mst_jns_perawatan;
use App\Models\Mst_kecamatan;
use App\Models\Mst_kelas;
use App\Models\Mst_kelurahan;
use App\Models\Mst_kota;
use App\Models\Mst_negara;
use App\Models\Mst_pangkat;
use App\Models\Mst_pekerjaan;
use App\Models\Mst_pelaksana;
use App\Models\Mst_pelaksana_poli;
use App\Models\Mst_pendidikan;
use App\Models\Mst_provinsi;
use App\Models\Mst_rs;
use App\Models\Mst_ruangan;
use App\Models\Mst_ruangan_bed;
use App\Models\Mst_status_nikah;
use App\Models\Mst_suku;
use App\Models\Mst_waktu_pelayanan;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class MasterController extends BaseController
{

    public function Rs()
    {
        return LibApp::response(200, Mst_rs::all());
    }

    public function AwalanNama()
    {
        return LibApp::response(200, Mst_awalan::all());
    }

    public function Negara()
    {
        return LibApp::response(200, Mst_negara::all());
    }

    public function Provinsi()
    {
        return LibApp::response(200, Mst_provinsi::all());
    }

    public function Kota($idProvinsi)
    {
        $data = Mst_kota::with('r_provinsi')->where('id_provinsi', $idProvinsi)->get();
        return LibApp::response(200, $data);
    }

    public function Kecamatan($idKota)
    {
        $data = Mst_kecamatan::with('r_kota')->where('id_kota', $idKota)->get();
        return LibApp::response(200, $data);
    }

    public function Kelurahan($idKecamatan)
    {
        $data = Mst_kelurahan::with('r_kecamatan')->where('id_kecamatan', $idKecamatan)->get();
        return LibApp::response_success($data);
    }

    public function Suku()
    {
        return LibApp::response(200, Mst_suku::all());
    }

    public function StatusNikah()
    {
        return LibApp::response(200, Mst_status_nikah::all());
    }

    public function Agama()
    {
        return LibApp::response(200, Mst_agama::all());
    }

    public function Pekerjaan()
    {
        return LibApp::response(200, Mst_pekerjaan::all());
    }

    public function Pendidikan()
    {
        return LibApp::response(200, Mst_pendidikan::all());
    }

    public function Angkatan()
    {
        return LibApp::response(200, Mst_angkatan::all());
    }

    public function Pangkat()
    {
        return LibApp::response(200, Mst_pangkat::all());
    }

    public function GroupPasien()
    {
        return LibApp::response(200, Mst_grouppas::where('active', 1)->get());
    }

    public function GolonganPasien($idGroupPasien)
    {
        return LibApp::response(200, Mst_golpas::where('status', 1)->where('group', $idGroupPasien)->get());
    }

    public function Dokter()
    {
        $data = Mst_pelaksana_poli::GetAllData()->get();
        return LibApp::response(200, $data);
    }

    public function DokterByPoli($idRuangan)
    {
        $data = Mst_pelaksana_poli::GetAllData()->where('id_ruangan', $idRuangan)->get();

        return LibApp::response(200, $data);
    }



    public function Keluhan()
    {
        $data = DB::table('keluhan')->get();
        return LibApp::response_success($data);
    }

    public function JenisPerawatan()
    {
        return LibApp::response(200, Mst_jns_perawatan::where('active', 1)->get());
    }

    public function WaktuPelayanan()
    {
        return LibApp::response(200, Mst_waktu_pelayanan::all());
    }

    public function Ruangan($jnsPerawatan)
    {
        $query = Mst_ruangan::with('r_poli')->where('active', 1);
        if( $jnsPerawatan != 'all' )   {
            $query->where('jns_perawatan', $jnsPerawatan);
        }
        $data = $query->get();

        return LibApp::response(200, $data);
    }

    public function Kelas()
    {
        return LibApp::response(200, Mst_kelas::where('active', 1)->get());
    }

    public function TempatTidurByRuangan($idRuangan)
    {
        $this->idRuangan = $idRuangan;
        $data = Mst_ruangan_bed::with(['r_kelas_ruangan' => function($a){
                    return $a->with('r_ruangan', 'r_kelas');
                }])->whereHas('r_kelas_ruangan', function($aa){
                    return $aa->where('id_ruangan', $this->idRuangan);
                })->get();

        return LibApp::response(200, $data);
    }

    public function JnsPembayaran()
    {
        $data = Mst_jns_bayar::get();
        return LibApp::response(200, $data);
    }


}
