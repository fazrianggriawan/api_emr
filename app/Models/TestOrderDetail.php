<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TestOrderDetail extends Model
{
    protected $table = 'emr_test_order_detail';
    protected $primaryKey = 'id';

    public function saveData($data){
        try {
            return DB::table($this->table)
                ->insert($data);
        } catch (\Exception $e){
            return $e->getPrevious();
        }
    }

}
