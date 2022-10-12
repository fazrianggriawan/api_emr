<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi_request_rm extends Model
{
    protected $table        = 'registrasi_request_rm';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_pasien()
    {
        return $this->hasOne(Pasien::class, 'id', 'id_pasien');
    }

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan');
    }

    public function r_registrasi_antrian()
    {
        return $this->hasOne(Registrasi_antrian::class, 'noreg', 'noreg');
    }

}