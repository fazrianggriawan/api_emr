<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_harga_jasa extends Model
{
    protected $table        = 'tarif_harga_jasa';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_tarif_harga()
    {
        return $this->hasOne(Tarif_harga::class, 'id', 'id_tarif_harga');
    }

    public static function SaveData($jasa, $idTarifHarga)
    {
        foreach ($jasa as $row) {
            $insert = new Tarif_harga_jasa();
            $insert->id_tarif_harga = $idTarifHarga;
            $insert->jasa = $row['jasa'];
            $insert->id_group_jasa = $row['id_group_jasa'];
            $insert->save();
        }
    }

}
