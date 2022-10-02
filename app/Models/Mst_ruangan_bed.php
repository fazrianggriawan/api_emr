<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_ruangan_bed extends Model
{
    protected $table        = 'mst_ruangan_bed';
    protected $primaryKey   = 'id';

    public function r_kelas_ruangan()
    {
        return $this->hasOne(Mst_kelas_ruangan::class, 'id', 'id_kelas_ruangan');
    }
}
