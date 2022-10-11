<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App_user_pelaksana extends Model
{
    protected $table        = 'app_user_pelaksana';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function GetAllData()
    {
        return self::with(['r_user','r_pelaksana']);
    }

    public function r_user()
    {
        return $this->hasOne(App_user::class, 'id', 'id_user');
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana');
    }

}
