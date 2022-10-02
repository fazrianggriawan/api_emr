<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Antrian extends Model
{
    protected $table        = 'antrian';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_pasien()
    {
        return $this->hasOne(Pasien::class, 'id', 'id_pasien');
    }

    public static function SaveAntrian($request)
    {
        $antrian = new Antrian();

        $kodeBooking    = Antrian::GenerateKodeBooking();
        $pasien = Pasien::GetAllData()->where('id', $request->id_pasien)->first();
        $queryNoAntrian = Antrian::QueryNomorAntrian($request->id_ruangan, $request->a);
        $poliklinik     = Mst_poli::where('id_ruangan', $request->jadwalDokter['kodepoli'])->first();
        $golpas         = Mst_golpas::where('id', '')->first();

        $antrian->booking_code    = $kodeBooking;
        $antrian->nama            = $pasien->nama;
        $antrian->tgl_kunjungan   = $request->tglReg;
        $antrian->prefix_antrian  = $poliklinik->prefix_antrian;
        $antrian->no_antrian      = DB::raw($queryNoAntrian);
        $antrian->poli            = $poliklinik->kode_bpjs;
        $antrian->jns_pasien      = ((strtolower($request->jenisPembayaran) == 'bpjs') ? 'JKN' : 'NON JKN');
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
    }

    public function GenerateKodeBooking()
    {
        $bytes = random_bytes(3);
        return strtoupper(bin2hex($bytes));
    }

    public static function QueryNomorAntrian($request)
    {
        return '(SELECT COALESCE (MAX(aa.no_antrian)+1, 11) AS nomor_antrian FROM antrian AS aa WHERE aa.poli = "'.$request->jadwalDokter['kodepoli'].'" AND aa.tgl_kunjungan = "'.$request->jadwalDokter['tglKunjungan'].'")';
    }

}
