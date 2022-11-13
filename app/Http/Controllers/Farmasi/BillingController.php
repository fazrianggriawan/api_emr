<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Libraries\LibApp;
use App\Models\Farmasi_billing;
use App\Models\Farmasi_billing_pembayaran;
use App\Models\Farmasi_billing_pembayaran_detail;
use App\Models\Farmasi_opname_nama_obat;
use App\Models\Farmasi_opname_periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BillingController extends BaseController
{
    public function SaveBilling(Request $request)
    {
        $billing = new Farmasi_billing();
        $billing->noreg = $request->noreg;
        $billing->id_obat = $request->obat['id'];
        $billing->nama_obat = $request->obat['nama'];
        $billing->harga = $request->obat['harga'];
        $billing->satuan = $request->obat['satuan'];
        $billing->qty = $request->qty;
        $billing->id_farmasi_harga_obat = $request->obat['id_tarif_harga'];
        $save = $billing->save();
        if( $save ){
            return LibApp::response(200, ['noreg'=>$billing->noreg]);
        }
    }

    public function DeleteBilling(Request $request)
    {
        try {
            //code...
            Farmasi_billing::where('id', $request->id)->update(['active'=>0]);
            return LibApp::response(200, ['noreg'=>$request->noreg], 'Berhasil menghapus billing');
        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], 'Gagal Menghapus. '.$th->getMessage());
        }
    }

    public function SavePembayaran(Request $request)
    {
        DB::beginTransaction();

        try {
            //code...
            Farmasi_billing::where('noreg', $request->noreg)
                ->where('status', 'open')
                ->update(['status'=>'closed']);

            $billingOpen = Farmasi_billing::where('noreg', $request->noreg)->where('status', 'open')->get();

            foreach ($billingOpen as $row ) {
                $detailBilling = new Farmasi_billing_pembayaran_detail();
                $detailBilling->id_farmasi_billing = $row->id;
                $detailBilling->id_farmasi_billing_pembayaran = '';
                $detailBilling->dateCreated = '';


            }

            $pembayaran = new Farmasi_billing_pembayaran();
            $pembayaran->no_pembayaran = DB::raw('(SELECT CONCAT(\'FAR\',LPAD(COALESCE(MAX(no_pembayaran)+1, 000001),6,0)) as nomor FROM farmasi_billing_pembayaran as no_pembayaran)');
            $pembayaran->noreg = $request->noreg;
            $pembayaran->id_cara_bayar = $request->cara_bayar;
            $pembayaran->jumlah = $request->jumlah;
            $pembayaran->dateCreated = date('Y-m-d H:i:s');
            $pembayaran->save();

            DB::commit();
            return LibApp::response(200, ['noreg'=>$request->noreg]);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }

        $pembayaran = new Farmasi_billing_pembayaran();
        $billing = Farmasi_billing::where('noreg', $request->noreg)
                    ->where('status', 'open')
                    ->get();

    }

    public function GetBilling($noreg, $status)
    {
        $data = Farmasi_billing::where('noreg', $noreg)
                ->where('status', $status)
                ->where('active', 1)
                ->get();

        return LibApp::response(200, $data);

    }

    public function GetDataPembayaran($noreg)
    {
        $data = Farmasi_billing_pembayaran::where('noreg', $noreg)->get();
        return LibApp::response(200, $data);
    }

}
