<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Eklaim_group_category_tarif extends Model
{
    protected $table        = 'eklaim_group_category_tarif';
    protected $primaryKey   = 'id';

    public function r_category(){
        return $this->hasOne(Mst_category_tarif::class, 'id', 'id_mst_category_tarif');
    }

    public function r_group_eklaim()
    {
        return $this->hasOne(Eklaim_group_tarif::class, 'id', 'id_eklaim_group_tarif');
    }
}
