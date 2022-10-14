<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form_question_options extends Model
{
    protected $table = 'emr_form_question_options';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function r_question(){
        return $this->hasOne(Emr_form_question::class, 'id', 'id_form_question');
    }
}
