<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pangkat extends Model
{
    protected $table        = 'mst_pangkat';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'pangkat', 'id');
    }

}
