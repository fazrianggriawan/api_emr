<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_negara extends Model
{
    protected $table        = 'mst_negara';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'negara', 'id');
    }

}
