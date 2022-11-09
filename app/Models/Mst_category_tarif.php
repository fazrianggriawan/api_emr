<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_category_tarif extends Model
{
    protected $table        = 'mst_category_tarif';
    protected $primaryKey   = 'id';
    public $timestamps      = false;
    protected $keyType      = 'string';
}
