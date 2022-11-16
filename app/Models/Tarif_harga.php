<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_harga extends Model
{
    protected $table        = 'tarif_harga';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    public static $keyword;
    public static $category;
    public static $paket;

    public static function SaveData($idTarif, $harga, $sessionId)
    {
        $insert = new Tarif_harga();
        $insert->tarif_id = $idTarif;
        $insert->harga = $harga;
        $insert->session_id = $sessionId;
        $insert->save();
    }

    public function CariTarif()
    {
        $data = Tarif_harga::with([
            'r_tarif'=>function($q){
                return $q->with(['r_tarif_paket','r_tarif_category'=>function($q){
                    return $q->with(['r_cat_tarif', 'r_group_tarif'=>function($q){
                        return $q->with('r_group');
                    }]);
                }]);
            },
            'r_tarif_harga_jasa'
        ]);

        if( self::$category != 'all' ){
            $data->whereHas('r_tarif.r_tarif_category.r_group_tarif', function($q){
                return $q->where('id_mst_group_tarif', self::$category);
            });
        }

        if( self::$paket != 'all' ){
            $data->whereHas('r_tarif.r_tarif_paket', function($q){
                return $q->where('id_tarif_paket', self::$paket);
            });
        }

        $data->whereHas('r_tarif', function($q){
                    return $q->where('name', 'like', '%'.self::$keyword.'%')->where('active', 1)->orderBy('name');
                });

        $data->where('active', 1);

        return $data->get();
    }

    public function r_tarif()
    {
        return $this->hasOne(Tarif::class, 'id', 'tarif_id');
    }

    public function r_tarif_harga_jasa()
    {
        return $this->hasMany(Tarif_harga_jasa::class, 'id_tarif_harga', 'id');
    }

    public static function NonActive($idTarif)
    {
        return self::where('tarif_id', $idTarif)->update(['active'=>0]);
    }

}
