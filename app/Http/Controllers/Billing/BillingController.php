<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use App\Models\Billing;
use App\Models\Billing_detail;
use App\Models\Billing_detail_jasa;
use App\Models\Billing_discount_percent;
use App\Models\Billing_head;
use App\Models\Billing_jasa;
use App\Models\Billing_pembayaran;
use App\Models\Mst_ruangan;
use App\Models\Registrasi;
use App\Models\Tarif_harga_jasa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BillingController extends BaseController
{
    public function Save(Request $request)
    {

        $checkIt = DB::table('billing_pembayaran')->where('noreg', $request->noreg)->where('deleted', 0)->get();

        $checkIt = Billing_pembayaran::where('noreg', $request->noreg)->where('deleted', 0)->get();

        if( count($checkIt) == 0 ){

            DB::beginTransaction();

            try {

                $billingHead = Billing_head::where('session_id', $request->sessionId)->first();

                if( !$billingHead ){
                    $billingHead = Billing_head::SaveBillingHead($request);
                }

                $billingDetail = Billing_detail::SaveBillingDetail($billingHead, $request);

                Billing_detail_jasa::SaveJasa($billingDetail, $request->jasaPelaksana);

                DB::commit();

                return LibApp::response(200, ['idBillingHead'=> $billingHead->id], 'Berhasil Menambah '.$request->tarif['r_tarif']['name']);

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return LibApp::response(201, [], $e->getMessage());
            }
        }else{
            return LibApp::response(201, [], 'Billing Sudah Dibayar. Tidak dapat input billing');
        }
    }

    public function Delete(Request $request)
    {
        DB::beginTransaction();
        $delete = DB::table('billing')->where('id', $request->id)->update(['deleted' => 1]);

        $insert = DB::table('billing_delete')->insert(['id_billing'=>$request->id, 'dateCreated'=>date('Y-m-d H:i:s'), 'userCreated'=>'vclaim']);

        DB::commit();

        if( $delete ){
            return LibApp::response(200, [], 'Sukses');
        }
    }

    public function UpdateJumlah(Request $request)
    {
        $update = DB::table('billing')->where('id', $request->id)->update(['qty' => $request->qty]);
        if( $update ){
            return LibApp::response(200, [], 'Sukses');
        }
    }

    public function BillingByNoreg($noreg, $status)
    {
        Billing_detail::$noreg = $noreg;
        $data = Billing_detail::GetBilling();
        return LibApp::response(200, $data);
    }

    public function AddDiscount(Request $request)
    {
        $discount = Billing_discount_percent::where('id_billing', $request->id)->first();

        if( !$discount ){
            $discount = new Billing_discount_percent();
        }

        $discount->id_billing = $request->id;
        $discount->discount = $request->discount_percent;

        $save = $discount->save();

        if( $save ){
            return LibApp::response(200);
        }else{
            return LibApp::response(201);
        }
    }

    public function AddPembayaran(Request $request)
    {
        DB::beginTransaction();

        try {
            //code...
            $pembayaran = new Billing_pembayaran();

            $pembayaran->noreg = $request->noreg;
            $pembayaran->id_cara_bayar = $request->jnsPembayaran;
            $pembayaran->jumlah = str_replace(',', '', $request->jumlah);
            $pembayaran->dateCreated = date('Y-m-d H:i:s');
            $pembayaran->userCreated = 'demo';
            $save = $pembayaran->save();

            Registrasi::where('noreg', $request->noreg)->update(['status'=>'closed']);

            if( $save ){
                DB::commit();
                return LibApp::response(200);
            }else{
                return LibApp::response(201);
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201);
        }

    }

    public function DeletePembayaran(Request $request)
    {
        $update = Billing_pembayaran::where('id', $request->id)->update(['deleted'=>1]);

        if( $update ){
            return LibApp::response(200);
        }else{
            return LibApp::response(201);
        }

    }

    public function DataPembayaran($noreg)
    {
        $data = Billing_pembayaran::with('r_cara_bayar')->where('deleted', 0)->where('noreg', $noreg)->get();
        return LibApp::response(200, $data);
    }

    public function DeleteBilling(Request $request)
    {
        $delete = Billing::where('id', $request->id)->update(['deleted' => 1]);
        if( $delete ){
            return LibApp::response(200);
        }else{
            return LibApp::response(201);
        }
    }

    public function BillingByUnit($noreg, $unit)
    {
        Billing_detail::$noreg = $noreg;
        Billing_detail::$unit = strtoupper($unit);
        $data = Billing_detail::GetBilling();
        return LibApp::response(200, $data);
    }

    public function BillingByHead($idBillingHead)
    {

        Billing_detail::$idBillingHead = $idBillingHead;
        $data = Billing_detail::GetBilling();
        return LibApp::response(200, $data);

        $this->id = $idBillingHead;
        $data = Billing_detail::with([
            'r_billing_head',
            'r_tarif_harga' => function($q){
                return $q->with('r_tarif');
            },
            'r_billing_detail_jasa' => function($q){
                return $q->with('r_pelaksana');
            }
        ])->whereHas('r_billing_head', function($q){
            return $q->where('id', $this->id);
        })
        ->where('active', 1)
        ->get();

        return LibApp::response(200, $data);
    }

}