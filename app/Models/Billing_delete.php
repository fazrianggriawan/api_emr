<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_delete extends Model
{
    protected $table        = 'billing_delete';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveDelete($idBilling){
        return self::insert(['id_billing_detail' => $idBilling, 'dateCreated' => date('Y-m-d H:i:s'), 'userCreated' => 'user']);
    }

    public function r_billing_detail()
    {
        return $this->hasOne(Billing_detail::class, 'id', 'id_billing_detail');
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana');
    }
}
