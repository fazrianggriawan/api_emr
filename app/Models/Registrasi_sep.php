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

    public static function SaveData($noreg, $sep)
    {
        $insert = new Registrasi_sep();
        $insert->noreg = $noreg;
        $insert->no_sep = $sep;
        $insert->save();
    }

}
