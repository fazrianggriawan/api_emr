<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tarif extends Model
{
    protected $table = 'tarif';
    protected $primaryKey = 'id';

    public function r_tarif_harga()
    {
        return $this->hasMany(Tarif_harga::class, 'tarif_id', 'id');
    }

    public function r_tarif_category()
    {
        return $this->hasOne(Tarif_category::class, 'id_tarif', 'id');
    }

    public function TarifHarga($idTarifHarga)
    {
        return DB::table('tarif_harga_jasa')
                ->select('tarif_harga_jasa.id', 'tarif_harga_jasa.id_tarif_harga', 'tarif_harga_jasa.jasa', 'mst_group_jasa.name AS group_jasa_name', 'mst_group_jasa.id AS group_jasa_id')
                ->leftJoin('mst_group_jasa', 'tarif_harga_jasa.id_group_jasa', '=', 'mst_group_jasa.id')
                ->where('tarif_harga_jasa.id_tarif_harga', $idTarifHarga)
                ->get();
    }

    public function JasaTarif($idTarifHarga, $idGroupJasa)
    {
        return DB::table('tarif_harga_jasa')
                ->where('id_tarif_hargaa', $idTarifHarga)
                ->where('id_group_jasaa', $idGroupJasa)
                ->get();
    }

    function getData($noreg, $unit)
    {
        return DB::table($this->table)
            ->where('noreg', $noreg)
            ->where('unit', $unit)
            ->get();
    }

}
