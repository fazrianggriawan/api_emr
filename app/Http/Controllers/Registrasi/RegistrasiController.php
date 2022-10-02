<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use App\Models\Antrian;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class RegistrasiController extends BaseController
{

    public function GetRegistasi($noreg)
    {
        $data = Registrasi::GetAllData()->where('noreg', $noreg)->first();
        return LibApp::response(200, $data);
    }

    public function GetDataRegistrasi()
    {
        $data = Registrasi::GetAllData()->limit(25)->get();
        return LibApp::response(200, $data);
    }

    public function SaveRegistrasi(Request $request)
    {
        if( $check = Registrasi::IsRegistrasiOpen($request->idPasien, $request->tanggal) ){
            $message  = 'Pasien Telah Terdaftar <br/>';
            $message .= $check->jns_perawatan->name.'<br/>';
            $message .= ( strtolower($check->jns_perawatan->id) == 'rj' ) ? 'POLIKLINIK ' : 'RUANGAN ';
            $message .= $check->ruang_perawatan->name.' <br/>';
            $message .= 'Tanggal : '.LibApp::dateHuman($check->tglReg).'<br/>';
            $message .= 'No. Reg : '.$check->noreg.'<br/>';
            $message .= 'Status : '.ucfirst($check->status).'<br/>';
            return LibApp::response(201, $check, $message);
            exit;
        }

        DB::beginTransaction();

        try {
            $registrasi = new Registrasi();

            $sessionID = microtime(true);

            $queryNoreg = '(SELECT DISTINCTROW
					concat(
						DATE_FORMAT(now(), \''.strtoupper($request->jnsPerawatan).'%y%m%d\'),
						lpad(
							COALESCE (max(RIGHT(aa.noreg, 5)) + 1, 1),
							5,
							\'0\'
						)
					) as a
					FROM
						registrasi as aa
					WHERE
						LEFT (aa.noreg, 8) = DATE_FORMAT(now(), \''.strtoupper($request->jnsPerawatan).'%y%m%d\'))';

            $registrasi->noreg            = DB::raw($queryNoreg);
            $registrasi->id_pasien        = $request->idPasien;
            $registrasi->norm             = $request->norm;
            $registrasi->noAskes          = $request->noAsuransi;
            $registrasi->tglReg           = $request->tanggal;
            $registrasi->jamReg           = date('H:i:s');
            $registrasi->id_golpas        = $request->golPasien;
            $registrasi->rs               = $request->rs;
            $registrasi->dpjp_pelaksana   = $request->dokter;
            $registrasi->ruangan          = $request->ruanganPoli;
            $registrasi->id_jns_perawatan = $request->jnsPerawatan;
            $registrasi->status           = strtolower($request->status);
            $registrasi->userCreated      = 'user';
            $registrasi->dateCreated      = date('Y-m-d H:i:s');
            $registrasi->session_id       = $sessionID;

            $registrasi->save();

            $data = Registrasi::GetAllData()->where('session_id', $sessionID)->first();

            DB::commit();

            return LibApp::response(200, $data, 'Sukses');

        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, $th->getMessage(), 'Gagal');
        }
    }

}
