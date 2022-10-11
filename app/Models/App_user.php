<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App_user extends Model
{
    protected $table        = 'app_user';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function GetAllData()
    {
        return self::with(['r_module_user' => function($q){
            return $q->with('r_module');
        }, 'r_role_user' => function($q){
            return $q->with('r_role');
        }]);
    }

    public function r_module_user()
    {
        return $this->hasMany(App_module_user::class, 'id_user', 'id');
    }

    public function r_role_user()
    {
        return $this->hasOne(App_role_user::class, 'id_user', 'id');
    }

    public function r_user_pelaksana()
    {
        return $this->hasOne(App_user_pelaksana::class, 'id_user', 'id');
    }

}
