<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_provinsi extends Model
{
    protected $table        = 'mst_provinsi';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'provinsi', 'id');
    }

    public function kota()
    {
        return $this->hasMany(Mst_kota::class, 'id_provinsi', 'id');
    }

}
