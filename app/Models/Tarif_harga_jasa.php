<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_harga_jasa extends Model
{
    protected $table        = 'tarif_harga_jasa';
    protected $primaryKey   = 'id';

    public function r_tarif_harga()
    {
        return $this->hasOne(Tarif_harga::class, 'id', 'id_tarif_harga');
    }

}
