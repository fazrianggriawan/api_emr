<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing_detail extends Model
{
    protected $table        = 'billing_detail';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public static $noreg;
    public static $idBillingHead;
    public static $unit;
    public static $status;
    public static $idBillingPembayaran;

    public static function SaveBillingDetail($billingHead, $request){
        $data = new Billing_detail();
        $data->id_billing_head = $billingHead->id;
        $data->noreg = $billingHead->noreg;
        $data->id_tarif_harga = $request->tarif['id'];
        $data->qty = 1;
        $data->tanggal = $request->billingHead['tanggal'];
        $data->ruangan = $billingHead->tanggal;
        $data->dateCreated = date('Y-m-d H:i:s');
        $data->userCreated = 'user';
        $data->save();

        return $data;
    }

    public static function GetBilling()
    {
        $data = Billing_detail::with([
            'r_billing_head' => function($q){
                return $q->with(['r_ruangan','r_pelaksana','r_jns_perawatan']);
            },
            'r_tarif_harga' => function($q){
                return $q->with('r_tarif');
            },
            'r_billing_detail_jasa' => function($q){
                return $q->with('r_pelaksana');
            }
        ]);

        if( self::$idBillingHead ){
            $data = $data->whereHas('r_billing_head', function($q){
                return $q->where('id', self::$idBillingHead);
            });
        }

        if( self::$noreg ){
            $data = $data->whereHas('r_billing_head', function($q){
                return $q->where('noreg', self::$noreg);
            });
        }

        if( self::$unit ){
            $data = $data->whereHas('r_billing_head', function($q){
                return $q->where('unit', self::$unit);
            });
        }

        if( self::$idBillingPembayaran ){
            $data = $data->whereHas('r_billing_pembayaran_detail', function($q){
                return $q->where('id_billing_pembayaran', self::$idBillingPembayaran);
            });
        }

        if( self::$status ){
            $data = $data->where('status', self::$status);
        }

        return $data->where('active', 1)->get();
    }

    public static function UpdateStatus($billing, $status)
    {
        foreach ($billing as $row ) {
            $data = new Billing_detail();
            $data->where('id', $row['id'])->update(['status' => $status]);
        }
    }

    public function r_billing_head()
    {
        return $this->hasOne(Billing_head::class, 'id', 'id_billing_head');
    }

    public function r_tarif_harga()
    {
        return $this->hasOne(Tarif_harga::class, 'id', 'id_tarif_harga');
    }

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_billing_pembayaran_detail()
    {
        return $this->hasOne(Billing_pembayaran_detail::class, 'id_billing_detail', 'id');
    }

    public function r_billing_detail_jasa()
    {
        return $this->hasMany(Billing_detail_jasa::class, 'id_billing_detail', 'id');
    }

    public static function GetBillingHead()
    {
        return self::$idBillingHead;
    }
}
