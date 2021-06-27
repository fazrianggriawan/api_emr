<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Dokter extends Model
{
    protected $table = 'mst_dokter';
    protected $primaryKey = 'id';

    public function getAll(){
        return DB::table($this->table)->select('id',DB::raw('CONCAT(gelar_depan,\' \',nama,\' \',gelar_belakang) as name'))->get();
    }
}
