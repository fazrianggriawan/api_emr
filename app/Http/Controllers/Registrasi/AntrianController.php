<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use App\Models\Antrian;
use App\Models\Mst_poli;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;


class AntrianController extends BaseController
{
    public function SaveAntrian(Request $request)
    {
        DB::beginTransaction();

        try {

            $antrian = new Antrian();

            $kodeBooking    = Antrian::GenerateKodeBooking();
            $pasien = Pasien::GetAllData()->where('id', $request->id_pasien)->first();
            $queryNoAntrian = Antrian::QueryNomorAntrian($request);
            $poliklinik     = Mst_poli::where('id_ruangan', $request->jadwalDokter['kodepoli'])->first();

            $antrian->booking_code    = $kodeBooking;
            $antrian->nama            = $pasien->nama;
            $antrian->tgl_kunjungan   = $request->tglReg;
            $antrian->prefix_antrian  = $poliklinik->prefix_antrian;
            $antrian->no_antrian      = DB::raw($queryNoAntrian);
            $antrian->poli            = $poliklinik->kode_bpjs;
            $antrian->jns_pasien      = (($request->jenisPembayaran == 'bpjs') ? 'JKN' : 'NON JKN');
            $antrian->no_kartu_bpjs   = $request->pasien['noaskes'];
            $antrian->norm            = substr($request->pasien['norekmed'], -6);
            $antrian->no_referensi    = (isset($request->rujukan['noKunjungan'])) ? $request->rujukan['noKunjungan'] : '';
            $antrian->jns_kunjungan   = $request->jenisKunjungan['kode'];
            $antrian->jam_praktek     = $request->jadwalDokter['jadwal'];
            $antrian->kodedokter_bpjs = $request->jadwalDokter['kodedokter'];
            $antrian->hp              = (isset($request->rujukan['peserta']['mr']['noTelepon'])) ? $request->rujukan['peserta']['mr']['noTelepon'] : '';
            $antrian->nik             = (isset($request->rujukan['peserta']['nik'])) ? $request->rujukan['peserta']['nik'] : '';

            $antrian->save();

            return ( $antrian->save() ) ? $antrian : FALSE;

        } catch (\Throwable $th) {
            DB::rollBack();
            return LibApp::response(201, $th->getMessage(), 'Gagal');
        }

    }
}
