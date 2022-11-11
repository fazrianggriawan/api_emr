<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table        = 'registrasi';
    protected $primaryKey   = 'noreg';
    protected $keyType      = 'string';
    public $timestamps      = false;

    public static function GetAllData()
    {
        return self::with(['pasien' => function($q){
            return $q->with('r_jns_kelamin', 'r_golpas');
        },'registrasi_antrian' => function($q){
            return $q->with('r_antrian');
        },'golpas' => function($q){
            return $q->with('r_grouppas');
        },'rumah_sakit','dokter','ruang_perawatan','jns_perawatan']);
    }

    public static function IsRegistrasiOpen($idPasien, $tanggal)
    {
        return self::GetAllData()
                    ->where('id_pasien', $idPasien)
                    ->where('tglReg', $tanggal)
                    ->where('status', 'open')
                    ->first();
    }

    public static function StatusRegistrasi($noreg, $status)
    {
        return self::where('noreg', $noreg)->where('status', $status)->first();
    }

    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'id', 'id_pasien');
    }

    public function golpas()
    {
        return $this->hasOne(Mst_golpas::class, 'id', 'id_golpas')
                    ->select('id','name','group');
    }

    public function rumah_sakit()
    {
        return $this->hasOne(Mst_rs::class, 'id', 'rs')
                    ->select('id', 'name');
    }

    public function dokter()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'dpjp_pelaksana')
                    ->select('id', 'name');;
    }

    public function ruang_perawatan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'ruangan')
                    ->select('id', 'name');;
    }

    public function jns_perawatan()
    {
        return $this->hasOne(Mst_jns_perawatan::class, 'id', 'id_jns_perawatan')
                    ->select('id', 'name');;
    }

    public function registrasi_antrian()
    {
        return $this->hasOne(Registrasi_antrian::class, 'noreg', 'noreg');
    }
}
