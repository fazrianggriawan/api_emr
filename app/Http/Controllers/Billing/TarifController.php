<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class tarifController extends BaseController
{
    public function TarifByCategory($categoryId)
    {
        $data = DB::table('tarif_harga')
            ->select('tarif_harga.id AS id_tarif_harga', 'tarif.name AS tarif_name', 'tarif_harga.harga AS harga', 'mst_category_tarif.name AS category_name')
            ->leftJoin('tarif', 'tarif_harga.tarif_id', '=', 'tarif.id')
            ->leftJoin('tarif_category', 'tarif.id', '=', 'tarif_category.id_tarif')
            ->leftJoin('mst_category_tarif', 'tarif_category.id_category_tarif', '=', 'mst_category_tarif.id')
            ->where('mst_category_tarif.id', $categoryId)
            ->where('tarif_harga.active', 1)
            ->orderBy('tarif.name')
            ->get();

        return LibApp::response_success($data);
    }

    public function Category()
    {
        $data = DB::table('mst_category_tarif')->where('active', 1)->orderBy('name')->get();
        return LibApp::response_success($data);
    }

    public function TarifJasa($idTarifHarga)
    {
        $data = DB::table('tarif_harga_jasa')
                ->select('tarif_harga_jasa.id', 'tarif_harga_jasa.id_tarif_harga', 'tarif_harga_jasa.jasa', 'mst_group_jasa.name AS group_jasa_name', 'mst_group_jasa.id AS group_jasa_id')
                ->leftJoin('mst_group_jasa', 'tarif_harga_jasa.id_group_jasa', '=', 'mst_group_jasa.id')
                ->where('tarif_harga_jasa.id_tarif_harga', $idTarifHarga)
                ->get();
        return LibApp::response_success($data);
    }

    public function DefaultPelaksana(Request $request)
    {
        $data = DB::table('mst_ruangan_pelaksana')
                ->select('mst_ruangan_pelaksana.*', 'mst_pelaksana.name as pelaksana_name')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'mst_ruangan_pelaksana.id_pelaksana')
                ->where('id_ruangan', $request->idRuangan)
                ->whereIn('id_group_jasa', $request->idGroupJasa)
                ->get();
        return LibApp::response_success($data);
    }
}
