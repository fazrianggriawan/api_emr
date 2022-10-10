<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App_module_user extends Model
{
    protected $table        = 'app_module_user';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function GetAllData()
    {
        return self::with(['r_user', 'r_module']);
    }

    public function r_user()
    {
        return $this->hasOne(App_user::class, 'id', 'id_user');
    }

    public function r_module()
    {
        return $this->hasOne(App_module::class, 'id', 'id_module');
    }
}
