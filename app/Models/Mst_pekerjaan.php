<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pekerjaan extends Model
{
    protected $table        = 'mst_pekerjaan';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'pekerjaan', 'id');
    }

}
