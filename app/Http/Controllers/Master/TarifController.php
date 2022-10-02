<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Registrasi\RegistrasiController;
use App\Http\Libraries\LibApp;
use App\Models\Master;
use App\Models\Pelaksana;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class TarifController extends BaseController
{

    public function Tarif()
    {
        $data = DB::table('tarif')->get();
        return LibApp::response_success($data);
    }

    public function MasterCategory()
    {
        $data = DB::table('mst_category_tarif')->get();
        return LibApp::response_success($data);
    }

    public function MasterTarifHarga()
    {
        $data = DB::table('')->get();
        return LibApp::response_success($data);
    }

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
        $data = DB::table('mst_category_tarif')->orderBy('name')->get();
        return LibApp::response_success($data);
    }

    public function TarifJasa($idTarifHarga)
    {

        $cRegistrasi = new RegistrasiController();
        $mTarif = new Tarif();
        $mPelaksana = new Pelaksana();
        $mMaster = new Master();

        $data = $mTarif->TarifHarga($idTarifHarga);
        return LibApp::response_success($data);

    }

}
