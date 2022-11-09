<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Mst_group_tarif extends Model
{
    protected $table        = 'mst_group_tarif';
    protected $primaryKey   = 'id';
    protected $keyType      = 'string';
}
