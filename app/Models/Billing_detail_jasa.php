<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_detail_jasa extends Model
{
    protected $table        = 'billing_detail_jasa';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveJasa($billingDetail, $jasaPelaksana){
        foreach ($jasaPelaksana as $key => $value) {
            $data = new Billing_detail_jasa();
            $data->id_billing_detail = $billingDetail->id;
            $data->id_pelaksana = $value;
            $data->group = $key;
            $data->save();
        }
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
