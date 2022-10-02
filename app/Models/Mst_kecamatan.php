<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_kecamatan extends Model
{
    protected $table        = 'mst_kecamatan';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function r_kota()
    {
        return $this->hasOne(Mst_kota::class, 'id', 'id_kota');
    }

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'kecamatan', 'id');
    }

    public function kelurahan()
    {
        return $this->hasMany(Mst_kelurahan::class, 'id_kecamatan', 'id');
    }

}
