<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_pembayaran extends Model
{
    protected $table        = 'billing_pembayaran';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SavePembayaran($request)
    {
        $pembayaran = new Billing_pembayaran();
        $pembayaran->noreg = $request->noreg;
        $pembayaran->id_cara_bayar = $request->jnsPembayaran;
        $pembayaran->jumlah = str_replace(',', '', $request->jumlah);
        $pembayaran->dateCreated = date('Y-m-d H:i:s');
        $pembayaran->userCreated = 'user';
        return $pembayaran->save();
    }


    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_cara_bayar()
    {
        return $this->hasOne(Mst_cara_bayar::class, 'id', 'id_cara_bayar');
    }

}
