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

    public static function SaveAntrian($data)
    {
        $antrian = new Antrian();

        // $kodeBooking    = Antrian::GenerateKodeBooking();
        // $pasien         = Pasien::GetAllData()->where('id', $data->id_pasien)->first();
        $queryNoAntrian = Antrian::QueryNomorAntrian($data->ruangan, $data->tanggal);
        $ruangan        = Mst_ruangan::where('id_ruangan', $data->ruangan)->first();

        $antrian->kode_booking  = Self::GenerateKodeBooking();
        $antrian->id_pasien     = $data->id_pasien;
        $antrian->id_ruangan    = $data->ruangan;
        $antrian->tgl_kunjungan = $data->tanggal;
        $antrian->id_pelaksana  = $data->dokter;
        $antrian->prefix        = $ruangan->prefix;
        $antrian->nomor         = DB::raw($queryNoAntrian);

        $antrian->save();

        return ( $antrian->save() ) ? $antrian : FALSE;
    }

    public static function GenerateKodeBooking()
    {
        $bytes = random_bytes(3);
        return strtoupper(bin2hex($bytes));
    }

    public static function QueryNomorAntrian($ruangan, $tanggal)
    {
        return '(SELECT COALESCE (MAX(aa.nomor)+1, 11) AS nomor FROM antrian AS aa WHERE aa.id_ruangan = "'.$ruangan.'" AND aa.tgl_kunjungan = "'.$tanggal.'")';
    }

}
