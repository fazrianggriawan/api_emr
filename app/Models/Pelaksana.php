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

    public function PelaksanaGroup($idGroupJasa)
    {
        return DB::table('mst_pelaksana_group')
            ->select('mst_pelaksana.name as value', 'mst_pelaksana.id as key')
            ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'mst_pelaksana_group.id_pelaksana')
            ->where('mst_pelaksana_group.group', $idGroupJasa)
            ->get();
    }

    public function PelaksanaRuangan($idRuangan, $idGroupJasa)
    {
        return DB::table('mst_ruangan_pelaksana')
                ->select('mst_ruangan_pelaksana.*', 'mst_pelaksana.name as pelaksana_name')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'mst_ruangan_pelaksana.id_pelaksana')
                ->where('id_ruangan', $idRuangan)
                ->where('id_group_jasa', $idGroupJasa)
                ->get();
    }


}
