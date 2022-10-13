<?php

namespace App\Http\Controllers\Emr;

use App\Models\Emr_form_ruangan;
use Laravel\Lumen\Routing\Controller as BaseController;

class QuestionController extends BaseController
{
    public function Question()
    {
        $data = Emr_form_ruangan::with(['r_question','r_options' => function($q){
            return $q->with(['r_form_question_options']);
        }, 'r_child' => function($q){
            return $q->with(['r_ruangan', 'r_question', 'r_options' => function($q){
                return $q->with(['r_form_question_options']);
            }]);
        }])
        ->get();

        $res = array();
        $i = 0;
        foreach ($data as $key => $row ) {
            $res[$key] = $row->r_question;
            $res[$key]['id_parent'] = $row->id_parent;

            if($row->r_question->controlType == 'checkbox') {
                $res[$key]['value_checkbox'] = ($row->value == '0') ? FALSE : TRUE;
            }else{
                $res[$key]['value_checkbox'] = $row->value;
            }

            $res[$key]['options'] = $row->r_options;
            $res[$key]['child'] = Emr_form_ruangan::Parsing($row->r_child);

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
