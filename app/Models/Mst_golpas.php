<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mst_golpas extends Model
{
    protected $table        = 'mst_golpas';
    protected $primaryKey   = 'id';

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_golpas', 'id');
    }

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'gol_pasien', 'id');
    }

    public function r_grouppas()
    {
        return $this->hasOne(Mst_grouppas::class, 'id', 'group');
    }
}
