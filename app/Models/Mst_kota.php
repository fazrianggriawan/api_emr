<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_kota extends Model
{
    protected $table        = 'mst_kota';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'kota', 'id');
    }

    public function r_provinsi()
    {
        return $this->hasOne(Mst_provinsi::class, 'id', 'id_provinsi');
    }

    public function kecamatan()
    {
        return $this->hasMany(Mst_kecamatan::class, 'id_kota', 'id');
    }

}
