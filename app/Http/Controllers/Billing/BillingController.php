<?php

namespace App\Http\Controllers\Billing;

use App\Http\Libraries\LibApp;
use App\Models\Billing;
use App\Models\Master;
use App\Models\Tarif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class BillingController extends BaseController
{
    public function Save(Request $request)
    {
        $mRuangan = new Master();
        $mTarif = new Tarif();

        $idBilling = microtime(TRUE);

        $checkIt = DB::table('billing_pembayaran')->where('noreg', $request->noreg)->where('deleted', 0)->get();

        if( count($checkIt) == 0 ){

            $insert = array(
                'id' => $idBilling,
                'id_tarif_harga' => $request->tarif['id_tarif_harga'],
                'noreg' => $request->noreg,
                'qty' => 1,
                'tgl_tindakan' => $request->tanggal,
                'rs' => $mRuangan->RuanganById($request->ruangan)[0]->rs,
                'lokasi_tindakan' => $request->ruangan,
                'dateCreated' => date('Y-m-d H:i:s'),
                'userCreated' => 'user',
                'session_input' => $idBilling
            );

            $save = DB::table('billing')->insert($insert);

            foreach ($request->jasa as $key => $value) {
                if( $value ){
                    $jasa = $mTarif->JasaTarif($value, $key);
                    if( count($jasa) > 0 ){
                        $insert = array(
                            'id_billing' => $idBilling,
                            'id_tarif_harga_jasa' => $jasa[0]->id,
                            'id_pelaksana' => $value,
                            'dateCreated' => date('Y-m-d H:i:s'),
                            'userCreated' => 'user',
                            'session_id' => $idBilling
                        );
                        DB::table('billing_jasa')->insert($insert);
                    }
                }
            }

            return LibApp::response(200, $insert, 'sukses');
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
        $data = DB::table('billing')
                ->select('billing.*','tarif_harga.harga', 'tarif.name as  nama_tarif', DB::raw('COALESCE(billing_discount_percent.discount, 0) as discount'))
                ->leftJoin('tarif_harga', 'tarif_harga.id', '=', 'billing.id_tarif_harga')
                ->leftJoin('tarif', 'tarif.id', '=', 'tarif_harga.tarif_id')
                ->leftJoin('billing_discount_percent', 'billing_discount_percent.id_billing', '=', 'billing.id')
                ->where('billing.noreg', $noreg)
                ->where('deleted', 0)
                ->get();

        return LibApp::response(200, $data, 'sukses');
    }

    public function AddDiscount(Request $request)
    {
        $checkIt = DB::table('billing_discount_percent')->where('id_billing',$request->id)->get();
        if( count($checkIt) == 0 ){
            $add = DB::table('billing_discount_percent')->insert(['id_billing'=>$request->id, 'discount'=>$request->discount]);
        }else{
            $add = DB::table('billing_discount_percent')->where('id_billing', $request->id)->update(['id_billing'=>$request->id, 'discount'=>$request->discount]);
        }
        if( $add ){
            return LibApp::response(200, [], 'sukses');
        }
    }

    public function AddPembayaran(Request $request)
    {
        $insert = array(
            'noreg' => $request->noreg,
            'id_jns_bayar' => $request->jnsPembayaran,
            'jumlah' => $request->jumlah,
            'dateCreated' => date('Y-m-d H:i:s'),
            'userCreated' => 'demo'
        );

        $insert = DB::table('billing_pembayaran')->insert($insert);

        if( $insert ){
            return LibApp::response(200, [], 'sukses');
        }

    }

    public function DeletePembayaran(Request $request)
    {
        $update = DB::table('billing_pembayaran')->where('id', $request->id)->update(['deleted'=>1]);

        if( $update ){
            return LibApp::response(200, [], 'sukses');
        }

    }

    public function DataPembayaran($noreg)
    {
        $data = DB::table('billing_pembayaran')
                ->where('noreg', $noreg)
                ->where('deleted', 0)
                ->leftJoin('mst_jns_bayar', 'mst_jns_bayar.id', '=', 'billing_pembayaran.id_jns_bayar')
                ->get();
        return LibApp::response(200, $data, 'sukses');
    }



}
