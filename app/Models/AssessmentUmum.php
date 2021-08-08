<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AssessmentUmum extends Model
{
    protected $table = 'emr_assessment_umum';
    protected $primaryKey = 'id';

    public function saveData($data){
        return DB::table($this->table)
            ->insert($data);
    }
}
