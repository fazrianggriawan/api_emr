<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pelaksana extends Model
{
    protected $table = 'mst_pelaksana';
    protected $primaryKey = 'pelaksanaID';
    public $id_poli;

    public function getDokterByPoli(){
        return DB::table($this->table)
            ->where('id_poli', $this->id_poli)
            ->where('group', 'dokter')
            ->get();
    }
}
