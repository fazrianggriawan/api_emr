<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_discount_percent extends Model
{
    protected $table        = 'billing_discount_percent';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_billing()
    {
        return $this->hasOne(Billing::class, 'id_user', 'id');
    }

}
