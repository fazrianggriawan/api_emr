<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi_waktu_pelayanan extends Model
{
    protected $table      = 'registrasi_waktu_pelayanan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public static function SaveData($noreg, $data)
    {
        $insert = new Registrasi_waktu_pelayanan();
        $insert->noreg = $noreg;
        $insert->waktu_pelayanan = $data->waktuPelayanan;
        $insert->dateCreated = Date('Y-m-d H:i:s');
        $insert->userCreated = 'registrasi';
        $insert->save();
    }

}
