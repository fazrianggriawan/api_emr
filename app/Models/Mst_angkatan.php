<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_angkatan extends Model
{
    protected $table        = 'mst_angkatan';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'angkatan', 'id');
    }

}
