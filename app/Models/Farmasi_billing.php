<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Farmasi_billing extends Model
{
    protected $table        = 'farmasi_billing';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_farmasi_billing_pembayaran()
    {
        return $this->hasMany(Farmasi_billing_pembayaran::class, 'noreg', 'noreg');
    }

}
