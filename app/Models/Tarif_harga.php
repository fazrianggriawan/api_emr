<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_harga extends Model
{
    protected $table        = 'tarif_harga';
    protected $primaryKey   = 'id';

    public function r_tarif()
    {
        return $this->hasOne(Tarif::class, 'id', 'tarif_id');
    }

    public function r_tarif_harga_jasa()
    {
        return $this->hasMany(Tarif_harga_jasa::class, 'id_tarif_harga', 'id');
    }

}
