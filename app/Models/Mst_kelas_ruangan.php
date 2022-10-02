<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_kelas_ruangan extends Model
{
    protected $table        = 'mst_kelas_ruangan';
    protected $primaryKey   = 'id';

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan')
                    ->select('id', 'name');
    }

    public function r_kelas()
    {
        return $this->hasOne(Mst_kelas::class, 'id', 'id_kelas')
                    ->select('id', 'name');
    }
}
