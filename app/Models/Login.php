<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Login extends Model
{
    protected $table = 'login';
    protected $primaryKey = 'id';

    public function getLogin(){
        return DB::table($this->table)->get();
    }
}
