<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_awalan extends Model
{
    protected $table        = 'mst_awalan';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'awalan', 'id');
    }

}
