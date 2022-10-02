<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $table        = 'billing';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
    public $timestamps      = false;

    public static function GetAllData()
    {
        return self::with(['r_registrasi','r_ruangan',
                            'r_tarif_harga' => function($q){
                                return $q->with('r_tarif', 'r_tarif_harga_jasa');
                            },
                            'r_billing_jasa' => function($q){
                                return $q->with('r_tarif_harga_jasa', 'r_pelaksana');
                            }
                        ]);
    }

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_tarif_harga()
    {
        return $this->hasOne(Tarif_harga::class, 'id', 'id_tarif_harga');
    }

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'lokasi_tindakan');
    }

    public function r_billing_jasa()
    {
        return $this->hasMany(Billing_jasa::class, 'id_billing', 'id');
    }

}
