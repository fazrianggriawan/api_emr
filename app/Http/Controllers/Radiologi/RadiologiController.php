<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Controllers\Master\MasterController;
use App\Http\Libraries\LibApp;
use App\Models\Radiologi;
use App\Models\Radiologi_hasil_pemeriksaan;
use App\Models\Registrasi;
use App\Models\Tarif_harga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class RadiologiController extends BaseController
{
    function getAllMaster(){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllData(), '');
    }

    function getAllMasterDetail(Request $request){
        $mod = new Radiologi();
        return LibApp::response(200, $mod->getAllDataDetail($request->id_head), '');
    }

    public function DataOrder($tanggal, $status)
    {
        $data = DB::table('emr_test_order')
                ->select('emr_test_order.*', 'emr_status_order.status', 'pasien.nama', 'pasien.norm', 'mst_ruangan.name as nama_ruangan', 'mst_pelaksana.name as dokter')
                ->leftJoin('registrasi', 'registrasi.noreg', '=', 'emr_test_order.noreg')
                ->leftJoin('mst_ruangan', 'mst_ruangan.id', '=', 'registrasi.ruangan')
                ->leftJoin('pasien', 'pasien.id', '=', 'registrasi.id_pasien')
                ->leftJoin('mst_pelaksana', 'mst_pelaksana.id', '=', 'registrasi.dpjp_pelaksana')
                ->leftJoin(DB::raw('(SELECT * FROM emr_test_order_status WHERE active = 1 AND `status` = \''.$status.'\') as emr_status_order'), 'emr_status_order.id_emr_test_order', '=', 'emr_test_order.id')
                ->where(DB::raw('LEFT(emr_test_order.created_at, 10)'), $tanggal)
                ->groupBy('emr_test_order.noreg')
                ->get();

        return LibApp::response(200, $data, '');
    }

    public function HasilRadiologi($noreg)
    {
        $data = Registrasi::where('noreg', $noreg)->get();
        $img = '/nas-simrs/1-100002/RJ221012000001/RAD/11746_1.jpg';
        header('Content-Type: image/jpeg');
        return readfile($img);
    }

    public function CariTindakan($keyword)
    {
        $this->keyword = $keyword;

        $data = Tarif_harga::with([
                    'r_tarif'=>function($q){
                        return $q->with(['r_tarif_category']);
                    },
                    'r_tarif_harga_jasa'
                ])
                ->whereHas('r_tarif.r_tarif_category', function($q){
                    return $q->where('id_category_tarif', 'RAD');
                })
                ->whereHas('r_tarif', function($q){
                    return $q->where('name', 'like', '%'.$this->keyword.'%')->where('active', 1);
                })
                ->get();

        return LibApp::response(200, $data);
    }

    public function SaveHasil(Request $request)
    {
        try {
            DB::beginTransaction();

            if( $request->id != '' ){
                $update = new Radiologi_hasil_pemeriksaan();
                $update->where('id', $request->id)->update(['active'=>0]);
            }

            $hasil = new Radiologi_hasil_pemeriksaan();
            $hasil->tanggal = $request->tanggal;
            $hasil->noreg = $request->noreg;
            $hasil->kesimpulan = $request->kesimpulan;
            $hasil->id_billing_head = $request->idBillingHead;
            $hasil->id_pelaksana_dokter = $request->dokter;
            $hasil->dateCreated = date('Y-m-d H:i:s');
            $hasil->userCreated = 'user';

            $hasil->save();

            DB::commit();

            return LibApp::response(200, [], 'Berhasil menyimpan hasil.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function GetHasil($idBillingHead)
    {
        $data = Radiologi_hasil_pemeriksaan::where('id_billing_head', $idBillingHead)->where('active', 1)->first();
        return LibApp::response(200, $data);
    }


}
