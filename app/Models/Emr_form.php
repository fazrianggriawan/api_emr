<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form extends Model
{
    protected $table = 'emr_form';
    protected $primaryKey = 'id';

    public function r_ruangan(){
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan');
    }

}
