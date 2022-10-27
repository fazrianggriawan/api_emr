<?php

namespace App\Models\Simrs_lama;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Zbilling_kasir_jalan extends Model
{
    protected $table        = 'zbilling_kasir_jalan';
    protected $primaryKey   = 'sr_recno';
    protected $connection   = 'postgres';
    public $timestamps      = false;
}
