<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Billing_head extends Model
{
    protected $table        = 'billing_farmasi_head';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function GenerateId($depo)
    {
        return DB::raw('(SELECT CONCAT(\''.strtoupper($depo).'\',LPAD(COALESCE(MAX(RIGHT(id, 6))+1, 1),6,0)) as nomor FROM billing_head as aa WHERE unit = \''.strtoupper($depo).'\')');
    }

    public static function SaveBillingHead($request){
        $billingHead = new Billing_head();
        $billingHead->id = $billingHead->GenerateId($request->billingHead['unit']);
        $billingHead->noreg = $request->billingHead['noreg'];
        $billingHead->tanggal = $request->billingHead['tanggal'];
        $billingHead->status = $request->billingHead['status'];
        $billingHead->unit = strtoupper($request->billingHead['unit']);
        $billingHead->id_jns_perawatan = $request->billingHead['jnsPerawatan'];
        $billingHead->id_pelaksana_dokter = $request->billingHead['dokter'];
        $billingHead->id_ruangan = $request->billingHead['ruangan'];
        $billingHead->dateCreated = date('Y-m-d H:i:s');
        $billingHead->userCreated = 'user';
        $billingHead->session_id = $request->sessionId;
        $billingHead->save();

        return Billing_head::where('session_id', $request->sessionId)->first();
    }

    public function r_ruangan()
    {
        return $this->hasOne(Mst_ruangan::class, 'id', 'id_ruangan');
    }

    public function r_pelaksana()
    {
        return $this->hasOne(Mst_pelaksana::class, 'id', 'id_pelaksana_dokter');
    }

    public function r_jns_perawatan()
    {
        return $this->hasOne(Mst_jns_perawatan::class, 'id', 'id_jns_perawatan');
    }

}
