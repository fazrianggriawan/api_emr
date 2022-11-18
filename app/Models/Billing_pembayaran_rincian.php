<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_pembayaran_rincian extends Model
{
    protected $table        = 'billing_pembayaran_rincian';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveRincian($rincian, $noreg, $idBillingPembayaran)
    {
        foreach ($rincian as $row ) {
            $insert = new Billing_pembayaran_rincian();
            $insert->kode = $row['id'];
            $insert->keterangan = strtoupper($row['name']);
            $insert->jumlah = $row['jumlah'];
            $insert->id_billing_pembayaran = $idBillingPembayaran;
            $insert->noreg = $noreg;
            $insert->save();
        }
    }

    public static function InactiveRincian($noreg)
    {
        return self::where('noreg', $noreg)->update(['active', 0]);
    }

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_billing_pembayaran()
    {
        return $this->hasOne(Billing_pembayaran::class, 'id', 'id_billing_pembayaran');
    }

}
