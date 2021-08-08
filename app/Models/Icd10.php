<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Icd10 extends Model
{
    protected $table = 'mst_icd10';
    protected $primaryKey = 'icd10code';

    public function getIcd10(){
        return DB::table($this->table)->select('icd10code as id','deskripsi as name')->get();
    }
}
