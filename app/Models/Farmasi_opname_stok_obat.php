<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmasi_opname_stok_obat extends Model
{
    protected $table        = 'farmasi_opname_stok_obat';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_opname_nama_obat()
    {
        return $this->hasOne(Farmasi_opname_nama_obat::class, 'id', 'id_farmasi_opname_nama_obat');
    }
}
