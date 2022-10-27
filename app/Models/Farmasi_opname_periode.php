<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmasi_opname_periode extends Model
{
    protected $table        = 'farmasi_opname_periode';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_depo()
    {
        return $this->hasOne(Farmasi_depo::class, 'id', 'id_farmasi_depo');
    }
}
