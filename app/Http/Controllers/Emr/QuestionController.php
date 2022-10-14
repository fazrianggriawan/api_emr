<?php

namespace App\Http\Controllers\Emr;

use App\Http\Libraries\LibApp;
use App\Models\Emr_form_controltype;
use App\Models\Emr_form_question;
use App\Models\Emr_form_question_options;
use App\Models\Emr_form_ruangan;
use App\Models\Emr_form_ruangan_options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        }])->get();

        $res = array();
        $i = 0;
        foreach ($data as $key => $row ) {
            $res[$key] = $row->r_question;
            $res[$key]['id_parent'] = $row->id_parent;
            $res[$key]['key'] = $row->r_question->key.'_'.$row->id;

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

    public function SaveEmrFormRuangan(Request $request){
        DB::beginTransaction();
        try {
            $question = new  Emr_form_question();

            $question->key = $request->form['key'];
            $question->value = (is_array($request->value)) ? preg_replace('/\s+/', '_', $request->value['name']) : $request->value;
            $question->label = $request->form['pertanyaan'];
            $question->required = $request->required;
            $question->order = 1;
            $question->controlType = $request->form['controlType'];
            $question->type = $request->form['controlType'];
            $question->display = ($request->hideLabel)? 0 : 1;
            $question->prefix = $request->form['prefix'];
            $question->postfix = $request->form['postfix'];

            $question->save();


            $form_ruangan = new Emr_form_ruangan();

            $form_ruangan->id_emr_form_question = $question->id;
            $form_ruangan->id_ruangan = $request->id_ruangan;
            $form_ruangan->id_parent = $request->form['parent'];
            $form_ruangan->id_emr_form = $request->id_form;

            $save = $form_ruangan->save();

            if( $request->form['controlType'] == 'dropdown' ){
                foreach ($request->options as $row ) {
                    $opt = new Emr_form_question_options();
                    $opt->key = preg_replace('/\s+/', '_', $row['name']);
                    $opt->value = $row['name'];
                    $opt->save();

                    $optForm = new Emr_form_ruangan_options();
                    $optForm->id_emr_form_ruangan = $form_ruangan->id;
                    $optForm->id_emr_form_question_options = $opt->id;
                    $optForm->save();
                }
            }

            if( $save ){
                DB::commit();
                return LibApp::response(200, $question);
            }

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, $th->getMessage());
        }
    }

    public function ControlType()
    {
        $data = Emr_form_controltype::all();
        return LibApp::response(200, $data);
    }

    public function ParentByForm($id_form)
    {
        $data = Emr_form_ruangan::with(['r_question'])->where('id_emr_form', $id_form)->where('id_parent', null)->get();
        return LibApp::response(200, $data);
    }

}
