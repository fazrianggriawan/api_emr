<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Billing_pembayaran extends Model
{
    protected $table        = 'billing_pembayaran';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SavePembayaran($request, $registrasi, $sessionId)
    {
        $pembayaran = new Billing_pembayaran();
        $pembayaran->noreg = $request->noreg;
        $pembayaran->id_cara_bayar = $request->jnsPembayaran;
        $pembayaran->no_pembayaran = DB::raw(self::GenerataNomorNota($registrasi));
        $pembayaran->jumlah = str_replace(',', '', $request->jumlah);
        $pembayaran->dateCreated = date('Y-m-d H:i:s');
        $pembayaran->userCreated = 'user';
        $pembayaran->session_id = $sessionId;
        $pembayaran->save();
    }

    public static function UpdateStatusBillingDetail($dataBillingDetail, $status)
    {
        foreach ($dataBillingDetail as $row ) {
            Billing_detail::where('id', $row->id_billing_detail)->update(['status' => $status]);
        }
    }

    public function GenerataNomorNota($registrasi)
    {
        return '(SELECT DISTINCTROW
                    concat(
                        DATE_FORMAT(now(), \''.strtoupper('TR').'%y%m%d\'),
                        lpad(
                            COALESCE (max(RIGHT(aa.no_pembayaran, 5)) + 1, 1),
                            5,
                            \'0\'
                        )
                    ) as a
                    FROM
                        billing_pembayaran as aa
                    WHERE
                        LEFT (aa.no_pembayaran, 8) = DATE_FORMAT(now(), \''.strtoupper('TR').'%y%m%d\'))';
    }


    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_cara_bayar()
    {
        return $this->hasOne(Mst_cara_bayar::class, 'id', 'id_cara_bayar');
    }

    public function r_pembayaran_detail()
    {
        return $this->hasMany(Billing_pembayaran_detail::class, 'id_billing_pembayaran', 'id');
    }

}
