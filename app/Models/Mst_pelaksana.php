<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pelaksana extends Model
{
    protected $table        = 'mst_pelaksana';
    protected $primaryKey   = 'id';

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'dpjp_pelaksana', 'id');
    }

    public function r_pelaksana_poli()
    {
        return $this->hasOne(Mst_pelaksana_poli::class, 'id_pelaksana', 'id' );
    }


}
