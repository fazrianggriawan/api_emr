<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Registrasi\RegistrasiController;
use App\Http\Libraries\LibApp;
use App\Models\Master;
use App\Models\Pelaksana;
use App\Models\Registrasi;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class TarifController extends BaseController
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

    public function TarifJasa($idTarifHarga, $noreg, $ruangan)
    {

        $cRegistrasi = new RegistrasiController();
        $mTarif = new Tarif();
        $mPelaksana = new Pelaksana();
        $mMaster = new Master();

        $registrasi = Registrasi::where('noreg', $noreg)->first();

        $data = $mTarif->TarifHarga($idTarifHarga);
        $dataRuangan = $mMaster->RuanganById($ruangan);

        if (count($data) > 0) {
            $newData = array();
            $arrayGroupJasa = array();
            $i = 1;
            foreach ($data as $key => $value) {
                array_push($arrayGroupJasa, $value->group_jasa_id);

                $item = array(
                    'key' => "$value->group_jasa_id",
                    'label' => $value->group_jasa_name,
                    'value' => '',
                    'options' => $mPelaksana->PelaksanaGroup($value->group_jasa_id),
                    'order' => $i,
                    'display' => TRUE,
                    'required' => TRUE,
                    'jasa' => $value->jasa
                );

                if ($value->group_jasa_id == 'dokter') {
                    if ($registrasi->ruangan == $ruangan) {
                        $item['value'] = $registrasi->dpjp_pelaksana;
                    }else{
                        if( $dataRuangan[0]->jns_perawatan == 'ri' ){
                            $item['value'] = $registrasi->dpjp_pelaksana;
                        }
                    }
                } else {
                    $idPelaksana = $mPelaksana->PelaksanaRuangan($ruangan, $value->group_jasa_id)[0]->id_pelaksana;
                    foreach ($item['options'] as $key => $data) {
                        if( $data->key == $idPelaksana ){
                            $item['value'] = $idPelaksana;
                        }
                    }
                }

                array_push($newData, $item);
                $i++;
            }

            $groupJasa = DB::table('mst_group_jasa')->whereNotIn('id', $arrayGroupJasa)->get();

            foreach ($groupJasa as $key => $value) {
                $item = array(
                    'key' => "$value->id",
                    'label' => $value->name,
                    'value' => '',
                    'options' => [],
                    'order' => $i,
                    'display' => FALSE,
                    'required' => FALSE,
                    'jasa' => ''
                );
                array_push($newData, $item);
                $i++;
            }

            return LibApp::response_success($newData);
        }
    }

    public function DefaultPelaksana(Request $request)
    {
        $data = DB::table('mst_pelaksana_ruangan')
            ->select('mst_pelaksana_ruangan.*', 'mst_pelaksana.name as pelaksana_name')
            ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'mst_pelaksana_ruangan.id_pelaksana')
            ->where('id_ruangan', $request->idRuangan)
            ->whereIn('id_group_jasa', $request->idGroupJasa)
            ->get();
        return LibApp::response_success($data);
    }

}
