<?php

namespace App\Models\Simrs_lama;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Apotik_salak_det extends Model
{
    protected $table        = 'apotik_salak_det';
    protected $primaryKey   = 'sr_recno';
    protected $connection   = 'postgres';
    public $timestamps      = false;
}
