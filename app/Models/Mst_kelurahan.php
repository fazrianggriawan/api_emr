<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_kelurahan extends Model
{
    protected $table        = 'mst_kelurahan';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'kelurahan', 'id');
    }

    public function r_kecamatan()
    {
        return $this->hasOne(Mst_kecamatan::class, 'id', 'id_kecamatan');
    }

}
