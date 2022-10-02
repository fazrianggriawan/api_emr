<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_jns_perawatan extends Model
{
    protected $table        = 'mst_jns_perawatan';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function registrasi()
    {
        return $this->hasMany(Registrasi::class, 'id_jns_perawatan', 'id');
    }

}
