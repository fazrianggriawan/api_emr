<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class App_user_logged_in extends Model
{
    protected $table        = 'app_user_logged_in';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_user()
    {
        return $this->hasOne(App_user::class, 'id', 'id_user');
    }

}
