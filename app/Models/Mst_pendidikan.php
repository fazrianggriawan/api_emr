<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pendidikan extends Model
{
    protected $table        = 'mst_pendidikan';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'pendidikan', 'id');
    }

}
