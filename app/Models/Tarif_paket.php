<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_paket extends Model
{
    protected $table        = 'tarif_paket';
    protected $primaryKey   = 'id';

    public function r_tarif()
    {
        return $this->hasOne(Tarif::class, 'id', 'id_tarif');
    }

    public function r_paket()
    {
        return $this->hasMany(Tarif_harga_jasa::class, 'id_tarif_harga', 'id');
    }

}
