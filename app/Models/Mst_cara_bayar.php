<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_cara_bayar extends Model
{
    protected $table        = 'mst_cara_bayar';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
    public $timestamps      = false;

    public function r_billing_pembayaran()
    {
        return $this->hasMany(Billing_pembayaran::class, 'id_cara_bayar', 'id');
    }

}
