<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestOrderHead extends Model
{
    protected $table = 'emr_test_order_head';
    protected $primaryKey = 'id';

    public function getAll($noreg){
        return DB::table($this->table)
            ->where('noreg', $noreg)
            ->get();
    }

    public function saveData($data){
        try {
            return DB::table($this->table)
                ->insertGetId($data);
        } catch (\Exception $e){
            return $e->getPrevious();
        }
    }

}
