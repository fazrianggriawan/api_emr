<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi_antrian extends Model
{
    protected $table        = 'registrasi_antrian';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_antrian()
    {
        return $this->hasOne(Antrian::class, 'id', 'id_antrian');
    }

}
