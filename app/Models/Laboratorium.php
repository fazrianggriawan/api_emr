<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Laboratorium extends Model
{
    protected $table = 'mst_lab';
    protected $primaryKey = 'id';

    public function getAllData(){
        return DB::table($this->table)
            ->join('mst_lab_cat', $this->table.'.category', '=', 'mst_lab_cat.id')
            ->join('mst_lab_group', $this->table.'.group', '=', 'mst_lab_group.id')
            ->select($this->table.'.id', $this->table.'.name', 'mst_lab_cat.name as cat_name', 'mst_lab_group.name as group_name')
            ->get();
    }

    public function getAllDataCito(){
        return DB::table('mst_lab_cito')
            ->select($this->table.'.id',
                $this->table.'.name',
                'mst_lab_cito.cito_name',
                'mst_lab_cat.id as id_cat',
                'mst_lab_cat.name as cat_name',
                'mst_lab_group.id as id_group',
                'mst_lab_group.name as group_name')
            ->join('mst_lab', 'mst_lab_cito.id_mst_lab', '=', 'mst_lab.id')
            ->join('mst_lab_cat', $this->table.'.category', '=', 'mst_lab_cat.id')
            ->join('mst_lab_group', $this->table.'.group', '=', 'mst_lab_group.id')
            ->orderBy('mst_lab.group', 'ASC')
            ->orderBy('mst_lab.category', 'ASC');
    }

}
