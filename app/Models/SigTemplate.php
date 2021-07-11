<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SigTemplate extends Model
{
    protected $table = 'sig_template';
    protected $primaryKey = 'id';

    public function getAllData()
    {
        return DB::table($this->table)->get();
    }
}
