<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form_controltype extends Model
{
    protected $table = 'emr_form_controltype';
    protected $primaryKey = 'id';
    protected $keyType      = 'string';
    public $timestamps      = false;
}
