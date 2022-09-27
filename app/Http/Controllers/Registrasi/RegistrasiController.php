<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class RegistrasiController extends BaseController
{

    public function SaveRegistrasi(Request $post)
    {
        date_default_timezone_set('Asia/Jakarta');
        DB::beginTransaction();
        $queryNoreg = '(SELECT DISTINCTROW
					concat(
						DATE_FORMAT(now(), \''.strtoupper($post->registrasi['jnsPerawatan']).'%y%m%d\'),
						lpad(
							COALESCE (max(RIGHT(aa.noreg, 5)) + 1, 1),
							5,
							\'0\'
						)
					) as a
					FROM
						registrasi as aa
					WHERE
						LEFT (aa.noreg, 8) = DATE_FORMAT(now(), \''.strtoupper($post->registrasi['jnsPerawatan']).'%y%m%d\'))';
        $sessionID = microtime(true);
        $insert = array(
                    'noreg' => DB::raw($queryNoreg),
                    'id_pasien' => $post->pasien['id'],
                    'norm' => $post->pasien['norm'],
                    'noAskes' => $post->pasien['no_asuransi'],
                    'tglReg' => date('Y-m-d'),
                    'jamReg' => date('H:i:s'),
                    'id_golpas' => $post->registrasi['golPasien'],
                    'rs' => $post->registrasi['rs'],
                    'ruangan' => $post->registrasi['ruanganPoli'],
                    'id_jns_perawatan' => $post->registrasi['jnsPerawatan'],
                    'status' => strtolower($post->registrasi['status']),
                    'userCreated' => 'vclaim',
                    'dateCreated' => date('Y-m-d H:i:s'),
                    'session_id' => $sessionID
                );

        if( $post->registrasi['dokter'] ){
            $insert['dpjp_pelaksana'] = $post->registrasi['dokter'];
        }

        $status = DB::table('registrasi')->insert($insert);

        $data = DB::table('registrasi')
                ->select('registrasi.*', 'mst_ruangan.name as nama_ruangan', 'mst_pelaksana.name as nama_dpjp', 'pasien.nama as nama_pasien')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'registrasi.ruangan' )
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien' )
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'registrasi.dpjp_pelaksana' )
                ->where('session_id', $sessionID)->get();

        DB::commit();

        if( $status ){
            return LibApp::response(200, $data[0], 'success');
        }else{
            return LibApp::response(201, [], 'failed to save');
        }
    }

    public function GetRegistrasi($noreg)
    {
        $data = DB::table('registrasi')
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien')
                ->where('noreg', $noreg)->get();
        if( count($data) > 0 ){
            return LibApp::response(200, $data[0], 'success');
        }else{
            return LibApp::response(201, [], 'empty');
        }

    }

    public function GetDataRegistrasi(Request $request)
    {
        $data = DB::table('registrasi')
                ->select(
                    'registrasi.*',
                    'pasien.nama',
                    'mst_golpas.name as golpasName',
                    'mst_rs.name as rsName',
                    'mst_pelaksana.name as dokter',
                    'mst_ruangan.name as ruanganName',
                    'mst_jns_perawatan.name as jnsPerawatanName',)
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien')
                ->leftJoin('mst_golpas', 'mst_golpas.id', '=', 'registrasi.id_golpas')
                ->leftJoin('mst_rs', 'mst_rs.id', '=', 'registrasi.rs')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'registrasi.dpjp_pelaksana')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'registrasi.ruangan')
                ->leftJoin('mst_jns_perawatan', 'mst_jns_perawatan.id', '=', 'registrasi.id_jns_perawatan')
                ->get();
        if( count($data) > 0 ){
            return LibApp::response(200, $data, 'success');
        }else{
            return LibApp::response(201, [], 'empty');
        }
    }
}
