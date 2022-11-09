<?php

namespace App\Http\Controllers\Lab;

use App\Http\Libraries\LibApp;
use App\Models\Billing;
use App\Models\Billing_detail;
use App\Models\Billing_head;
use App\Models\Lab_hasil_pemeriksaan;
use App\Models\Lab_nama_hasil_rujukan;
use App\Models\Lab_nilai_rujukan_options;
use App\Models\Tarif_harga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class LaboratoriumController extends BaseController
{
    public function CariPemeriksaan($keyword)
    {
        $this->keyword = $keyword;

        $data = Tarif_harga::with([
                    'r_tarif'=>function($q){
                        return $q->with(['r_tarif_category']);
                    },
                    'r_tarif_harga_jasa'
                ])
                ->whereHas('r_tarif.r_tarif_category', function($q){
                    return $q->where('id_category_tarif', 'LAB');
                })
                ->whereHas('r_tarif', function($q){
                    return $q->where('name', 'like', '%'.$this->keyword.'%')->where('active', 1);
                })
                ->get();

        return LibApp::response(200, $data);
    }

    public function SavePemeriksaan(Request $request)
    {
        DB::beginTransaction();
        try {
            $microtime = microtime(TRUE);
            $billingHead = new Billing_head();
            $billingHead->id = DB::raw('(SELECT CONCAT(\''.strtoupper($request->form['unit']).'\',LPAD(COALESCE(MAX(RIGHT(id, 6))+1, 1),6,0)) as nomor FROM billing_head as aa WHERE unit = \''.strtoupper($request->form['unit']).'\')');
            $billingHead->noreg = $request->form['noreg'];
            $billingHead->tanggal = $request->tanggal;
            $billingHead->unit = strtoupper($request->form['unit']);
            $billingHead->status = $request->form['status'];
            $billingHead->id_jns_perawatan = $request->form['jnsPerawatan'];
            $billingHead->id_pelaksana_dokter = $request->form['dokter'];
            $billingHead->id_ruangan = $request->form['ruangan'];
            $billingHead->dateCreated = date('Y-m-d H:i:s');
            $billingHead->session_id = $microtime;
            $billingHead->save();

            $data = $billingHead->where('session_id', $microtime)->first();

            foreach ($request->data as $row) {
                $billingDetail = new Billing_detail();
                $billingDetail->id_billing_head = $data->id;
                $billingDetail->noreg = $billingHead->noreg;
                $billingDetail->id_tarif_harga = $row['tarif']['id'];
                $billingDetail->qty = 1;
                $billingDetail->tanggal = $billingHead->tanggal;
                $billingDetail->ruangan = $billingHead->id_ruangan;
                $billingDetail->dateCreated = date('Y-m-d H:i:s');
                $billingDetail->save();
            }

            DB::commit();

            return LibApp::response(200, ['noreg'=>$billingHead->noreg, 'unit'=>$billingHead->unit]);

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }

    }


}
