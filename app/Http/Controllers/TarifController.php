<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class tarifController extends BaseController
{
    public function GetByCategory($categoryId)
    {
        $data = DB::table('tarif_harga')
                ->select('tarif.namaTarif', 'tarif_harga.harga', 'cat_tarif.namaCatTarif', 'cat_tarif.cat_tarifID', 'tarif_harga.id')
                ->where('tarif_cat.cat_tarifID', $categoryId)
                ->leftJoin('tarif', 'tarif_harga.tarifID', '=', 'tarif.tarifID')
                ->leftJoin('tarif_cat', 'tarif_harga.tarifID', '=', 'tarif_cat.tarifID')
                ->leftJoin('cat_tarif', 'tarif_cat.cat_tarifID', '=', 'cat_tarif.cat_tarifID')
                ->get();

        return LibApp::response_success($data);
    }

    public function Category()
    {
        $data = DB::table('cat_tarif')->where('active', 1)->orderBy('namaCatTarif')->get();
        return LibApp::response_success($data);
    }

}
