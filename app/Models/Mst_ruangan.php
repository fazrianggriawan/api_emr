<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_ruangan extends Model
{
    protected $table        = 'mst_ruangan';
    protected $primaryKey   = 'id';

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'ruangan', 'id');
    }

    public function r_jns_perawatan()
    {
        return $this->hasOne(Mst_jns_perawatan::class, 'id', 'jns_perawatan');
    }

    public function r_emr_form()
    {
        return $this->hasMany(Emr_form::class, 'id_ruangan', 'id');
    }

}
