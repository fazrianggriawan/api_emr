<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_status_nikah extends Model
{
    protected $table        = 'mst_status_nikah';
    protected $primaryKey   = 'id';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'status_nikah', 'id');
    }

}
