<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Icd9 extends Model
{
    protected $table = 'mst_icd9';
    protected $primaryKey = 'icd9code';

    public function getAllData(){
        return DB::table($this->table)
            ->select('icd9code as id',DB::raw('CONCAT(icd9code,\' - \',deskripsi) as name'))
            ->where('stsaktif', 'A')
            ->get();
    }
}
