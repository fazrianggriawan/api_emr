<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif_category extends Model
{
    protected $table        = 'tarif_category';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_cat_tarif()
    {
        return $this->hasOne(Mst_category_tarif::class, 'id', 'id_category_tarif');
    }

}
