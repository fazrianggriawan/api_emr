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

}
