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

}
