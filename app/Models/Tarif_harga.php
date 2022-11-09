<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_harga extends Model
{
    protected $table        = 'tarif_harga';
    protected $primaryKey   = 'id';
    public static $keyword;
    public static $category;
    public static $paket;

    public function CariTarif()
    {
        $data = Tarif_harga::with([
            'r_tarif'=>function($q){
                return $q->with(['r_tarif_paket','r_tarif_category'=>function($q2){
                    return $q2->with(['r_cat_tarif', 'r_group_tarif'=>function($q3){
                        return $q3->with('r_group');
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

}
