<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Radiologi extends Model
{
    protected $table = 'mst_radiologi_detail';
    protected $primaryKey = 'id';

    public function getAllData(){
        return DB::table($this->table)->get();
    }

    public function getAllDataDetail($id_head){
        return DB::table('mst_radiologi_detail')->where('id_mst_radiologi', $id_head)->get();
    }
}
