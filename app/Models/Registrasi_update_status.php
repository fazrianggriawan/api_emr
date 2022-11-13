<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi_update_status extends Model
{
    protected $table      = 'registrasi_update_status';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function SaveUpdateStatus($data)
    {
        $insert = new Registrasi_update_status();
        $insert->noreg = $data->noreg;
        $insert->status = $data->status;
        $insert->dateCreated = Date('Y-m-d H:i:s');
        $insert->userCreated = $data->username;
        $insert->save();
    }

}
