<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Keluhan extends Model
{
    protected $table = 'dt_template';
    protected $primaryKey = 'id';

    public function getTpl()
    {
        return DB::table($this->table)
            ->where('active', 1)
            ->get();
    }

}
