<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use App\Models\Antrian;
use App\Models\Mst_ruangan;
use App\Models\Registrasi;
use App\Models\Registrasi_antrian;
use App\Models\Registrasi_pulang_perawatan;
use App\Models\Registrasi_request_rm;
use App\Models\Registrasi_sep;
use App\Models\Registrasi_update_status;
use App\Models\Registrasi_waktu_pelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;
use stdClass;

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
        // if( $check = Registrasi::IsRegistrasiOpen($request->idPasien, $request->tanggal) ){
        //     $message  = 'Pasien Telah Terdaftar <br/>';
        //     $message .= $check->jns_perawatan->name.'<br/>';
        //     $message .= ( strtolower($check->jns_perawatan->id) == 'rj' ) ? 'POLIKLINIK ' : 'RUANGAN ';
        //     $message .= $check->ruang_perawatan->name.' <br/>';
        //     $message .= 'Tanggal : '.LibApp::dateHuman($check->tglReg).'<br/>';
        //     $message .= 'No. Reg : '.$check->noreg.'<br/>';
        //     $message .= 'Status : '.ucfirst($check->status).'<br/>';
        //     return LibApp::response(201, $check, $message);
        // }

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

            if( $request->noSep != '' ){
                $registrasiSep = new Registrasi_sep();
                $registrasiSep->noreg = $data->noreg;
                $registrasiSep->no_sep = $request->noSep;
                $registrasiSep->dateCreated = $registrasi->dateCreated;
                $registrasiSep->save();
            }

            // Antrian
            $antrian = new Antrian();
            $queryNoAntrian = $antrian->QueryNomorAntrian($request->ruanganPoli, $request->tanggal, $request->dokter);
            $ruangan        = Mst_ruangan::where('id', $request->ruanganPoli)->first();

            $antrian->kode_booking  = $antrian->GenerateKodeBooking();
            $antrian->id_pasien     = $request->idPasien;
            $antrian->id_ruangan    = $request->ruanganPoli;
            $antrian->tgl_kunjungan = $request->tanggal;
            $antrian->id_pelaksana  = $request->dokter;
            $antrian->prefix        = $ruangan->prefix_antrian;
            $antrian->nomor         = DB::raw($queryNoAntrian);

            $antrian->save();

            // Registasi to Antrian
            $registrasiAntrian = new Registrasi_antrian();
            $registrasiAntrian->noreg = $data->noreg;
            $registrasiAntrian->id_antrian = $antrian->id;
            $registrasiAntrian->save();

            // Registrasi Waktu Pelayanan
            Registrasi_waktu_pelayanan::SaveData($registrasiAntrian->noreg, $request);

            // Registasi to Request RM
            $regRequest = new Registrasi_request_rm();
            $regRequest->noreg = $data->noreg;
            $regRequest->id_pasien = $request->idPasien;
            $regRequest->id_ruangan = $request->ruanganPoli;
            $regRequest->dateCreated = date('Y-m-d H:i:s');
            $regRequest->save();

            DB::commit();

            return LibApp::response(200, $data, 'Sukses');

        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, $th->getMessage(), 'Gagal '.$th->getMessage());
        }
    }

    public function FilterDataRegistrasi(Request $request)
    {
        $data = Registrasi::GetAllData();
        $this->request = $request;
        if( $request->nama ) $data->whereHas('pasien', function($q){
            return $q->where('nama' , 'like', '%'.$this->request->nama.'%');
        });
        if( $request->noreg ) $data->where('noreg', $request->noreg);
        if( $request->jnsPerawatan ) $data->where('id_jns_perawatan' , $request->jnsPerawatan);
        if( $request->ruangan ) $data->where('ruangan', $request->ruangan);
        if( $request->dokter ) $data->where('dpjp_pelaksana', $request->dokter);
        if( $request->jnsPembayaran ) $data->whereHas('golpas', function($q){
            return $q->where('group', $this->request->jnsPembayaran);
        });
        if( $request->norm ) $data->whereHas('pasien', function($q){
            return $q->where('norm', $this->request->norm);
        });
        if( $request->from ){
            $data->where('tglReg', '>=', $request->from );
            if( !isset($request->to) ) $data->where('tglReg', '<=', $request->from );
        }
        if( isset($request->to) ) $data->where('tglReg', '<=', $request->to );

        $data = $data->get();

        return LibApp::response(200, $data, 'Sukses');
    }

    public function CancelRegistrasi(Request $request)
    {
        try {
            //code...
            DB::beginTransaction();

            Registrasi::CancelRegistrasi($request);

            $data = new stdClass();
            $data->noreg = $request->noreg;
            $data->status = 'canceled';
            $data->username = $request->username;

            Registrasi_update_status::SaveUpdateStatus($data);

            DB::commit();
            return LibApp::response(200, [], 'Berhasil Membatalkan Registrasi.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, [], 'Gagal Membatalkan Registrasi. '.$th->getMessage());
            //throw $th;
        }
    }

    public function UpdateRegistrasi(Request $request)
    {
        try {
            DB::beginTransaction();
            Registrasi::UpdateRegistrasi($request);

            $data = new stdClass();
            $data->noreg = $request->noreg;
            $data->status = 'update';
            $data->username = $request->username;

            Registrasi_update_status::SaveUpdateStatus($data);

            DB::commit();
            return LibApp::response(200, [], 'Berhasil Merubah Registrasi.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, [], 'Gagal Merubah Registrasi. '.$th->getMessage());
            //throw $th;
        }
    }

    public function PulangPerawatan(Request $request)
    {
        try {
            DB::beginTransaction();
            if( $request->id == null ){
                Registrasi_pulang_perawatan::SaveData($request);
            }else{
                Registrasi_pulang_perawatan::UpdateData($request);
            }

            DB::commit();
            return LibApp::response(200, [], 'Berhasil Menyimpan Data Pulang Perawatan.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function TanggalPulang($noreg)
    {
        try {
            $data = Registrasi_pulang_perawatan::where('noreg', $noreg)->first();
            return LibApp::response(200, $data);
        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }
    }


}
