<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_category extends Model
{
    protected $table        = 'tarif_category';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveData($idTarif, $idCategory)
    {
        $insert = new Tarif_category();
        $insert->id_tarif = $idTarif;
        $insert->id_category_tarif = $idCategory;
        $insert->save();
    }

    public static function UpdateData($id, $idTarif, $idCategory)
    {
        return self::where('id', $id)->update(['id_tarif' => $idTarif, 'id_category_tarif' => $idCategory]);
    }

    public function r_cat_tarif()
    {
        return $this->hasOne(Mst_category_tarif::class, 'id', 'id_category_tarif');
    }

    public function r_group_tarif()
    {
        return $this->hasOne(Tarif_category_group::class, 'id_category_tarif', 'id_category_tarif');
    }

    public function r_eklaim_group_tarif()
    {
        return $this->hasOne(Eklaim_group_category_tarif::class, 'id_mst_category_tarif', 'id_category_tarif');
    }
}
