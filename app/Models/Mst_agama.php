<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_agama extends Model
{
    protected $table        = 'mst_agama';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'agama', 'id');
    }

}
