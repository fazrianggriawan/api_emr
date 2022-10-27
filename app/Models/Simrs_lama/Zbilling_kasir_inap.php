<?php

namespace App\Models\Simrs_lama;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Zbilling_kasir_inap extends Model
{
    protected $table        = 'zbilling_kasir_inap';
    protected $primaryKey   = 'sr_recno';
    protected $connection   = 'postgres';
    public $timestamps      = false;
}
