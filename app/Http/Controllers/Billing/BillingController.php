<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use App\Models\Billing;
use App\Models\Billing_discount_percent;
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
                $billing = new Billing();

                $idBilling = microtime(TRUE);

                $billing->id              = $idBilling;
                $billing->id_tarif_harga  = $request->tarif['id_tarif_harga'];
                $billing->noreg           = $request->noreg;
                $billing->qty             = 1;
                $billing->tgl_tindakan    = $request->tanggal;
                $billing->rs              = Mst_ruangan::where('id', $request->ruangan)->first()->rs;
                $billing->lokasi_tindakan = $request->ruangan;
                $billing->dateCreated     = date('Y-m-d H:i:s');
                $billing->userCreated     = 'user';
                $billing->session_input   = $idBilling;

                $billing->save();

                $insertBillingJasa = array();

                foreach ($request->jasa as $id_group_jasa => $pelaksana) {
                    if( $pelaksana ){
                        $id_tarif_harga_jasa = Tarif_harga_jasa::where('id_tarif_harga', $request->tarif['id_tarif_harga'])
                                            ->where('id_group_jasa', $id_group_jasa)
                                            ->first()->id;

                        $array = array(
                            'id_billing' => $idBilling,
                            'id_tarif_harga_jasa' => $id_tarif_harga_jasa,
                            'id_pelaksana' => $pelaksana,
                            'dateCreated' => date('Y-m-d H:i:s'),
                            'userCreated' => 'user',
                            'session_id' => $idBilling
                        );

                        array_push($insertBillingJasa, $array);
                    }
                }

                if( count($insertBillingJasa) > 0 ){
                    Billing_jasa::insert($insertBillingJasa);
                }

                DB::commit();

                return LibApp::response(200, [], 'Sukses');

            } catch (\Exception $e) {
                DB::rollback();
                // something went wrong
                return LibApp::response(201, [], 'Gagal menyimpan');
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

    public function BillingByNoreg($noreg)
    {
        $data = Billing::with(['r_registrasi',
                               'r_ruangan',
                               'r_discount_percent',
                               'r_tarif_harga' => function($q){
                                    return $q->with('r_tarif', 'r_tarif_harga_jasa');
                                },
                                'r_billing_jasa' => function($q){
                                    return $q->with('r_tarif_harga_jasa', 'r_pelaksana');
                                }])
                        ->where('noreg', $noreg)
                        ->where('deleted', 0)
                        ->orderBy('dateCreated', 'desc')
                        ->get();

        $res = array();
        foreach ($data as $key => $value) {
            $res[$key] = $value;
            if( !$value->r_discount_percent ){
                $res[$key]['discount_percent'] = 0;
            }else{
                $res[$key]['discount_percent'] = $value->r_discount_percent->discount;
            }
        }

        return LibApp::response(200, $res);
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



}
