<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Emr_form_ruangan extends Model
{
    protected $table = 'emr_form_ruangan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function r_ruangan(){
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan');
    }

    public function r_question(){
        return $this->hasOne(Emr_form_question::class, 'id', 'id_emr_form_question');
    }

    public function r_options(){
        return $this->hasMany(Emr_form_ruangan_options::class, 'id_emr_form_ruangan', 'id');
    }

    public function r_child(){
        return $this->hasMany(self::class, 'id_parent', 'id');
    }

    public static function Parsing($data)
    {
        $res = array();

        foreach ($data as $key => $row ) {
            $res[$key] = $row->r_question;
            $res[$key]['key'] = $row->r_question->key.'_'.$row->id;

            if($row->r_question->controlType == 'checkbox') {
                $res[$key]['value_checkbox'] = ($row->value == '0') ? FALSE : TRUE;
            }else{
                $res[$key]['value_checkbox'] = $row->value;
            }

            $res[$key]['options'] = $row->r_options;
            foreach ($row->r_options as $key2 => $value2) {
                $res[$key]['options'][$key2] = array(
                        'key' => $value2->r_form_question_options->key,
                        'value' => $value2->r_form_question_options->value
                    );
            }
        }

        return $res;
    }

}
