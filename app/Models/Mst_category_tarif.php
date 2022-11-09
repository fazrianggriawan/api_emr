<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_category_tarif extends Model
{
    protected $table        = 'mst_category_tarif';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_group()
    {
        return $this->hasOne(Tarif_category_group::class, 'id', 'id_mst_category_tarif');
    }

}
