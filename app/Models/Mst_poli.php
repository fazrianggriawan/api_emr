<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_poli extends Model
{
    protected $table        = 'mst_poli';
    protected $primaryKey   = 'id_ruangan';

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id_ruangan', 'id_ruangan')
                ->select('id_ruangan', 'name');
    }

}
