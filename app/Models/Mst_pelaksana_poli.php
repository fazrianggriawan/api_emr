<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mst_pelaksana_poli extends Model
{
    protected $table        = 'mst_pelaksana_poli';
    protected $primaryKey   = 'id';

    public static function GetAllData()
    {
        return self::with('r_pelaksana', 'r_ruangan')->whereHas('r_pelaksana',
                    function ($query) {
                        return $query->where('group', '=', 'dokter');
                    });
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana')
                    ->select('id','name');
    }

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan')
                    ->select('id','name');
    }

}
