<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Registrasi_pulang_perawatan extends Model
{
    protected $table        = 'registrasi_pulang_perawatan';
    protected $primaryKey   = 'id';
    public $timestamps      = false;

    public static function SaveData($request)
    {
        $insert = new Registrasi_pulang_perawatan();
        $insert->noreg = $request->noreg;
        $insert->tanggal = $request->tanggal;
        $insert->id_keterangan_pulang = $request->keteranganPulang;
        $insert->catatan = $request->catatan;
        $insert->dateCreated = date('Y-m-d H:i:s');
        $insert->userCreated = 'registrasi';
        $insert->save();
    }

    public static function GetDataByNoreg($noreg)
    {
        return self::where('noreg', $noreg)->first();
    }

    public function r_registrasi()
    {
        return $this->hasMany(Registrasi::class, 'noreg', 'noreg');
    }

    public function r_keterangan_pulang()
    {
        return $this->hasMany(Mst_keterangan_pulang::class, 'id', 'id_keterangan_pulang');
    }

}
