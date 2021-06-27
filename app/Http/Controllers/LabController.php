<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Models\Laboratorium;
use phpDocumentor\Reflection\Types\Object_;

class LabController extends BaseController
{
    function getAllMaster(){
        $lab = new Laboratorium();
        $data = $lab->getAllData();
        $collection = collect($data);
        $grouped = $collection->groupBy(array('group_name','cat_name'))->toArray();
        return $grouped;
    }

    function getAllMasterCito(){
        $lab = new Laboratorium();
        $data = $lab->getAllDataCito()->get();
         $collection = collect($data);
        $grouped = $collection->groupBy(array('group_name','cat_name'))->toArray();
        $array = array();
        foreach ($grouped as $key=>$item) {
            $aa = array('name'=>$key);
            if( is_array($item) ){
                foreach ($item as $key2=>$item2) {
                    $aa['category']['name'] = $key2;
                    $aa['category']['items'] = $item2;
                }
            }
            array_push($array, $aa);
        }
        return $grouped;
    }

}
