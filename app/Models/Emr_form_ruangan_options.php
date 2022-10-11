<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form_ruangan_options extends Model
{
    protected $table = 'emr_form_ruangan_options';
    protected $primaryKey = 'id';

    public function r_form_ruangan(){
        return $this->hasOne(Emr_form_ruangan::class, 'id', 'id_emr_form_ruangan');
    }

    public function r_form_question_options(){
        return $this->hasOne(Emr_form_question_options::class, 'id', 'id_emr_form_question_options');
    }
}
