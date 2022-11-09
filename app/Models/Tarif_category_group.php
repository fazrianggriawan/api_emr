<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_category_group extends Model
{
    protected $table        = 'tarif_category_group';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_group()
    {
        return $this->hasOne(Mst_group_tarif::class, 'id', 'id_mst_group_tarif');
    }

    public function r_category()
    {
        return $this->hasOne(Mst_category_tarif::class, 'id', 'id_category_tarif');
    }

}
