<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_jns_kelamin extends Model
{
    protected $table        = 'mst_jns_kelamin';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';

    public function pasien()
    {
        return $this->hasMany(Pasien::class, 'jns_kelamin', 'id');
    }

}
