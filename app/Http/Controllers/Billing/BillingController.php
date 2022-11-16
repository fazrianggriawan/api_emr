<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use App\Models\Billing;
use App\Models\Billing_delete;
use App\Models\Billing_detail;
use App\Models\Billing_detail_jasa;
use App\Models\Billing_discount_percent;
use App\Models\Billing_head;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BillingController extends BaseController
{
    public function Save(Request $request)
    {

        // $checkIt = Registrasi::StatusRegistrasi($request->billingHead['noreg'], 'closed');
        $checkIt = FALSE;

        if ( !$checkIt ) {

            DB::beginTransaction();

            try {

                $billingHead = Billing_head::where('session_id', $request->sessionId)->first();

                if (!$billingHead) {
                    $billingHead = Billing_head::SaveBillingHead($request);
                }

                $billingDetail = Billing_detail::SaveBillingDetail($billingHead, $request);

                Billing_detail_jasa::SaveJasa($billingDetail, $request->jasaPelaksana);

                DB::commit();

                return LibApp::response(200, ['idBillingHead' => $billingHead->id], 'Berhasil Menambah ' . $request->tarif['r_tarif']['name']);
            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return LibApp::response(201, [], $e->getMessage());
            }
        } else {
            return LibApp::response(201, [], 'Tidak Bisa Menambah Billing. Registrasi Sudah Closed');
        }
    }

    public function Delete(Request $request)
    {

        // $checkIt = Registrasi::StatusRegistrasi($request->noreg, 'closed');
        // if($checkIt){
        //     return LibApp::response(201, [], 'Gagal Menghapus. Registrasi Sudah Closed');
        // };

        try {

            DB::beginTransaction();

            Billing_detail::where('id', $request->id)->update(['active' => 0]);
            Billing_delete::SaveDelete($request->id);

            DB::commit();

            return LibApp::response(200, ['noreg'=>$request->noreg], 'Berhasil Menghapus Billing.');

        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], 'Gagal Menghapus. '.$th->getMessage());
        }
    }

    public function UpdateJumlah(Request $request)
    {
        try {
            DB::beginTransaction();

            Billing_detail::where('id', $request->id)->update(['qty' => $request->qty]);

            DB::commit();

            return LibApp::response(200, ['noreg'=>$request->noreg], 'Berhasil Merubah Jumlah Billing.');

        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], 'Gagal Merubah. '.$th->getMessage());
        }
    }

    public function BillingByNoreg($noreg, $status)
    {
        Billing_detail::$noreg = $noreg;
        Billing_detail::$status = $status;
        $data = Billing_detail::GetBilling();
        return LibApp::response(200, $data);
    }

    public function AddDiscount(Request $request)
    {
        $discount = Billing_discount_percent::where('id_billing', $request->id)->first();

        if (!$discount) {
            $discount = new Billing_discount_percent();
        }

        $discount->id_billing = $request->id;
        $discount->discount = $request->discount_percent;

        $save = $discount->save();

        if ($save) {
            return LibApp::response(200);
        } else {
            return LibApp::response(201);
        }
    }

    public function DeleteBilling(Request $request)
    {
        $delete = Billing::where('id', $request->id)->update(['deleted' => 1]);
        if ($delete) {
            return LibApp::response(200);
        } else {
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
            'r_tarif_harga' => function ($q) {
                return $q->with('r_tarif');
            },
            'r_billing_detail_jasa' => function ($q) {
                return $q->with('r_pelaksana');
            }
        ])->whereHas('r_billing_head', function ($q) {
            return $q->where('id', $this->id);
        })
            ->where('active', 1)
            ->get();

        return LibApp::response(200, $data);
    }
}
