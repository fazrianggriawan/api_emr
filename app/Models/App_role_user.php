<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App_role_user extends Model
{
    protected $table        = 'app_role_user';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_role()
    {
        return $this->hasOne(App_role::class, 'id', 'id_role');
    }

    public function r_user()
    {
        return $this->hasOne(App_user::class, 'id', 'id_user');
    }
}
