<?php
namespace App\Http\Controllers\MedicalRecord;

use App\Models\AssessmentUmum;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class AssessmentUmumController extends BaseController{
    public function save(Request $request)
    {
        $tanggal = $request->input('tanggal');

        $mod = new AssessmentUmum();

        try {
            $mod->noreg = $request['noreg'];
            $mod->asal_kunjungan_id = @$request['asalKunjungan']['id'];
            $mod->asal_kunjungan_name = @$request['asalKunjungan']['name'];
            $mod->keluhan_utama_id = @$request['keluhanUtama']['id'];
            $mod->keluhan_utama_name = @$request['keluhanUtama']['name'];
            $mod->keluhan_utama_sejak = $request['keluhanUtamaSejak'];
            $mod->keluhan_tambahan_id = @$request['keluhanTambahan']['id'];
            $mod->keluhan_tambahan_name = @$request['keluhanTambahan']['name'];
            $mod->keluhan_tambahan_sejak = $request['keluhanTambahanSejak'];
            $mod->bb = $request['bb'];
            $mod->tb = $request['tb'];
            $mod->td = $request['td'];
            $mod->nadi = $request['nadi'];
            $mod->p = $request['p'];
            $mod->suhu = $request['suhu'];
            $mod->riwayat_penyakit_skrg = $request['riwayatPenyakitSkrg'];
            $mod->riwayat_penyakit_dulu = $request['riwayatPenyakitDulu'];
            $mod->alergi_obat = $request['alergiObat'];
            $mod->alergi_makanan = $request['alergiMakanan'];
            $mod->anamnesa_perawat = $request['anamnesaPerawat'];
            $mod->diagnosa_rujukan = $request['diagnosaRujukan'];
            $mod->save();

        }catch (\Exception $exception){
            dd($exception->getMessage());
        }


//        if( $json->metadata->status == 200 ){
//            if( isset($json->response) ){
//                return json_encode(array('status'=>200, 'data'=>$json->response->data, 'message'=>''));
//            }else{
//                return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
//            }
//        }else{
//            return json_encode(array('status'=>204, 'data'=>'', 'message'=>$json->metadata->message));
//        }
    }

    public function getData(Request $request)
    {
        $mod = new AssessmentUmum();
        $data = $mod->getData($request['noreg']);
        return json_encode($data);
    }

    public function getImage()
    {
        $img = './images/body.jpg';
        header('Content-Type: image/jpeg');
        return readfile($img);
    }

}
