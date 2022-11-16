<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Registrasi\RegistrasiController;
use App\Http\Libraries\LibApp;
use App\Models\Master;
use App\Models\Mst_group_jasa;
use App\Models\Mst_group_tarif;
use App\Models\Pelaksana;
use App\Models\Tarif;
use App\Models\Tarif_category;
use App\Models\Tarif_category_group;
use App\Models\Tarif_harga;
use App\Models\Tarif_harga_jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class TarifController extends BaseController
{

    public function Save(Request $request)
    {
        try {
            DB::beginTransaction();

            $sessionId = microtime(true);

            $idTarif = 0;

            if( isset($request->tarif['id']) ){
                $idTarif = $request->tarif['id'];
                Tarif::UpdateData($request->nama, $idTarif);
                Tarif_category::UpdateData($request->tarif['r_tarif_category']['id'], $idTarif, $request->category);
            }else{
                $newTarif = Tarif::SaveData($request->nama, $sessionId);
                $idTarif = $newTarif->id;
                Tarif_category::SaveData($idTarif, $request->category);
            }

            Tarif_harga::NonActive($idTarif);
            Tarif_harga::SaveData($idTarif, $request->harga, $sessionId);
            $newTarifHarga = Tarif_harga::where('session_id', $sessionId)->first();
            Tarif_harga_jasa::SaveData($request->jasa, $newTarifHarga->id);

            DB::commit();

            return LibApp::response(200, [], 'Tarif Berhasil Disimpan');
        } catch (\Throwable $th) {
            //throw $th;
            DB:: rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function CategoryByGroup($idGroup)
    {
        $data = Tarif_category_group::with(['r_category'])->where('id_mst_group_tarif', $idGroup)->get();
        return LibApp::response(200, $data);
    }

    public function Tarif()
    {
        $data = Tarif::with([
            'r_tarif_category'=>function($q){
                return $q->with(['r_cat_tarif','r_group_tarif'=>function($q){
                    return $q->with(['r_group']);
                }]);
            },
            'r_tarif_harga'=>function($q){
                return $q->with(['r_tarif_harga_jasa'=>function($q){
                    return $q->select(DB::raw('id, id_tarif_harga, SUM(jasa) as total'))->groupBy('id_tarif_harga');
                }]);
            }])->whereHas('r_tarif_harga.r_tarif_harga_jasa', function($q){
                return $q->where('active',1);
            })->orderBy('name')->get();
        return LibApp::response(200, $data);
    }

    public function DetailTarif($id)
    {
        $data = Tarif::with([
            'r_tarif_category'=>function($q){
                return $q->with(['r_cat_tarif','r_group_tarif'=>function($q){
                    return $q->with(['r_group']);
                }]);
            },
            'r_tarif_harga'=>function($q){
                return $q->with(['r_tarif_harga_jasa']);
            }])
            ->where('id', $id)
            ->first();

        return LibApp::response(200, $data);

    }

    public function TarifHarga($idTarif)
    {
        $data = Tarif_harga::with(['r_tarif','r_tarif_harga_jasa'])->where('tarif_id', $idTarif)->get();
        return LibApp::response(200, $data);
    }

    public function GroupTarif()
    {
        $data = Mst_group_tarif::get();
        return LibApp::response(200, $data);
    }

    public function MasterCategory()
    {
        $data = DB::table('mst_category_tarif')->get();
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
        $mTarif = new Tarif();

        $data = $mTarif->TarifHarga($idTarifHarga);
        return LibApp::response_success($data);
    }

    public function GroupJasa()
    {
        $data = Mst_group_jasa::where('active', 1)->get();
        return LibApp::response(200, $data);
    }

}
