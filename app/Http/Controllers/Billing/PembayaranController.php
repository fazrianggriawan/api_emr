<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use App\Models\Billing_detail;
use App\Models\Billing_pembayaran;
use App\Models\Registrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class PembayaranController extends BaseController
{

    public function AddPembayaran(Request $request)
    {
        DB::beginTransaction();

        try {
            //code...
            $status = ($request->jnsPembayaran == 'bpjs' || $request->jnsPembayaran == 'asu') ? 'credit' : 'closed';

            $checkIt = Billing_pembayaran::where('noreg', $request->noreg)->where('active', 1)->get();

            if (count($checkIt) == 0) {
                Registrasi::where('noreg', $request->noreg)->update(['status' => $status]);
            }

           Billing_pembayaran::SavePembayaran($request);

            Billing_detail::where('noreg', $request->noreg)->where('status', 'open')->update(['status' => $status]);

            DB::commit();
            return LibApp::response(200, ['noreg' => $request->noreg], 'Berhasil Menyimpan Pembayaran.');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], 'Gagal Menyimpan. ' . $th->getMessage());
        }
    }

    public function DeletePembayaran(Request $request)
    {
        $update = Billing_pembayaran::where('id', $request->id)->update(['active' => 1]);

        if ($update) {
            return LibApp::response(200);
        } else {
            return LibApp::response(201);
        }
    }

    public function DataPembayaran($noreg)
    {
        $data = Billing_pembayaran::with('r_cara_bayar')->where('noreg', $noreg)->where('active', 1)->get();
        return LibApp::response(200, $data);
    }

}
