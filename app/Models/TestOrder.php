<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestOrder extends Model
{
    protected $table = 'emr_test_order';
    protected $primaryKey = 'id';

    function getData($noreg, $unit)
    {
        return DB::table($this->table)
            ->where('noreg', $noreg)
            ->where('unit', $unit)
            ->get();
    }

}
