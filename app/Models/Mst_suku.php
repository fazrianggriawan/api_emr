<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_suku extends Model
{
    protected $table        = 'mst_suku';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'suku', 'id');
    }

}
