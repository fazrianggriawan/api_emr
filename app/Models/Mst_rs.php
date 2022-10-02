<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_rs extends Model
{
    protected $table        = 'mst_rs';
    protected $primaryKey   = 'id';

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_rs', 'id');
    }

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'rs', 'id');
    }

}
