<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    protected $table        = 'pasien';
    protected $primaryKey   = 'id';

    public static function GetAllData()
    {
        return self::with('r_rs',
                          'r_jns_kelamin',
                          'r_negara',
                          'r_provinsi',
                          'r_kota',
                          'r_kecamatan',
                          'r_kelurahan',
                          'r_suku',
                          'r_status_nikah',
                          'r_agama',
                          'r_pekerjaan',
                          'r_pendidikan',
                          'r_angkatan',
                          'r_pangkat',
                          'r_group_pasien',
                          'r_golpas');
    }

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_pasien', 'id');
    }

    public function r_rs()
    {
        return $this->hasOne(Mst_rs::class, 'id', 'rs');
    }

    public function r_jns_kelamin()
    {
        return $this->hasOne(Mst_jns_kelamin::class, 'id', 'jns_kelamin');
    }

    public function r_negara()
    {
        return $this->hasOne(Mst_negara::class, 'id', 'negara');
    }

    public function r_provinsi()
    {
        return $this->hasOne(Mst_provinsi::class, 'id', 'provinsi');
    }

    public function r_kota()
    {
        return $this->hasOne(Mst_kota::class, 'id', 'kota');
    }

    public function r_kecamatan()
    {
        return $this->hasOne(Mst_kecamatan::class, 'id', 'kecamatan');
    }

    public function r_kelurahan()
    {
        return $this->hasOne(Mst_kelurahan::class, 'id', 'kelurahan');
    }

    public function r_suku()
    {
        return $this->hasOne(Mst_suku::class, 'id', 'suku');
    }

    public function r_status_nikah()
    {
        return $this->hasOne(Mst_status_nikah::class, 'id', 'status_nikah');
    }

    public function r_agama()
    {
        return $this->hasOne(Mst_agama::class, 'id', 'agama');
    }

    public function r_pekerjaan()
    {
        return $this->hasOne(Mst_pekerjaan::class, 'id', 'pekerjaan');
    }

    public function r_pendidikan()
    {
        return $this->hasOne(Mst_pendidikan::class, 'id', 'pendidikan');
    }

    public function r_angkatan()
    {
        return $this->hasOne(Mst_angkatan::class, 'id', 'angkatan');
    }

    public function r_pangkat()
    {
        return $this->hasOne(Mst_pangkat::class, 'id', 'pangkat');
    }

    public function r_group_pasien()
    {
        return $this->hasOne(Mst_grouppas::class, 'id', 'group_pasien');
    }

    public function r_golpas()
    {
        return $this->hasOne(Mst_golpas::class, 'id', 'gol_pasien');
    }
}
