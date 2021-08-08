<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PatAnatomi extends Model
{
    protected $table = 'mst_pat_anatomi';
    protected $primaryKey = 'id';

    public function getAllData(){
        return DB::table($this->table)->get();
    }
}
