<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pelaksana_ruangan extends Model
{
    protected $table        = 'mst_pelaksana_ruangan';
    protected $primaryKey   = 'id';

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan');
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana');
    }

}
