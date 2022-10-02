<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_jasa extends Model
{
    protected $table        = 'billing_jasa';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_billing()
    {
        return $this->hasOne(Billing::class, 'id', 'id_billing');
    }

    public function r_tarif_harga_jasa()
    {
        return $this->hasOne(Tarif_harga_jasa::class, 'id', 'id_tarif_harga_jasa');
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana');
    }

}
