<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Master extends Model
{
    public function RuanganById($id)
    {
        return DB::table('mst_ruangan')
                ->where('id', $id)
                ->get();
    }

}
