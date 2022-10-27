<?php

namespace App\Models\Simrs_lama;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master_pasien extends Model
{
    protected $table        = 'master_pasien';
    protected $primaryKey   = 'sr_recno';
    protected $connection   = 'postgres';
    public $timestamps      = false;
}
