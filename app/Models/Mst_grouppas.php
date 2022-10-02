<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_grouppas extends Model
{
    protected $table        = 'mst_grouppas';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'group_pasien', 'id');
    }

}
