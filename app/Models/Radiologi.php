<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Radiologi extends Model
{
    protected $table = 'mst_radiologi';
    protected $primaryKey = 'id';

    public function getAllData(){
        return DB::table($this->table)->get();
    }
}
