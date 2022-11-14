<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_pembayaran_detail extends Model
{
    protected $table        = 'billing_pembayaran_detail';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveBilling($dataBilling, $dataPembayaran)
    {
        foreach ($dataBilling as $row ) {
            $data = new Billing_pembayaran_detail();
            $data->id_billing_pembayaran = $dataPembayaran->id;
            $data->id_billing_detail = $row['id'];
            $data->dateCreated = date('Y-m-d H:i:s');
            $data->userCreated = 'user';
            $data->save();
        }
    }

    public function r_billing_detail()
    {
        return $this->hasOne(Billing_detail::class, 'id', 'id_billing_detail');
    }

    public function r_billing_pembayaran()
    {
        return $this->hasOne(Billing_pembayaran::class, 'id', 'id_billing_pembayaran');
    }

}
