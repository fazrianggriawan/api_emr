<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form_question extends Model
{
    protected $table = 'emr_form_question';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function r_options(){
        return $this->hasMany(Emr_form_question_options::class, 'id', 'id_form_question');
    }
}
