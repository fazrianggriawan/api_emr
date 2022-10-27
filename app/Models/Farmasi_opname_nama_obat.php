<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmasi_opname_nama_obat extends Model
{
    protected $table        = 'farmasi_opname_nama_obat';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_periode()
    {
        return $this->hasOne(Farmasi_opname_periode::class, 'id', 'id_farmasi_opname_periode');
    }

    public function r_nama_obat()
    {
        return $this->hasOne(Farmasi_nama_obat::class, 'id', 'id_farmasi_nama_obat');
    }

    public function r_stok_obat()
    {
        return $this->hasOne(Farmasi_opname_stok_obat::class, 'id_farmasi_opname_nama_obat', 'id');
    }
}
