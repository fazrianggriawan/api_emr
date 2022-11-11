<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi_sep extends Model
{
    protected $table        = 'registrasi_sep';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public function r_registrasi()
    {
        return $this->hasOne(Registrasi::class, 'noreg', 'noreg');
    }

}
