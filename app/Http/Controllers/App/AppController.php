<?php

namespace App\Http\Controllers\App;

use App\Http\Libraries\LibApp;
use App\Models\App_module_user;
use App\Models\App_user;
use App\Models\App_user_pelaksana;
use Laravel\Lumen\Routing\Controller as BaseController;

class AppController extends BaseController
{
    public function ModuleByUsername($username)
    {
        $this->username = $username;
        $data = App_module_user::with(['r_module', 'r_user'])->whereHas('r_user', function($q){
            return $q->where('username', $this->username);
        })->get();

        if( $data ){
            $array = array();
            foreach ($data as $row) {
                $array[] = array(
                    'label' => $row->r_module->name,
                    'routerLink' => $row->r_module->router,
                );
            }

            return LibApp::response(200, $array);
        }
    }

    public function RuanganByUsername($username)
    {
        $this->username = $username;
        $data = App_user_pelaksana::with(['r_user', 'r_pelaksana'=>function($q){
            return $q->with('r_pelaksana_poli');
        }])->whereHas('r_user', function($q){
            return $q->where('username', $this->username);
        })->first();

        $data = array('id_pelaksana'=> $data->r_pelaksana->id, 'id_ruangan' => $data->r_pelaksana->r_pelaksana_poli->id_ruangan);

        return LibApp::response(200, $data);
    }

}
