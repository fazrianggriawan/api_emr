<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Libraries\LibApp;
use App\Models\Registrasi;
use App\Models\Simrs_lama\Master_pasien;
use App\Models\Simrs_lama\Zbilling_kasir_inap;
use App\Models\Simrs_lama\Zbilling_kasir_jalan;
use Laravel\Lumen\Routing\Controller as BaseController;


class RiwayatController extends BaseController
{
    public function GetData($idPasien, $norm)
    {
        $norekmed = str_pad($norm, 10, '0', STR_PAD_LEFT);

        $data = array();

        $this->norm = $norm;
        $registrasi = Registrasi::GetAllData()->wherehas('pasien', function($q){
            return $q->where('norm', $this->norm);
        })->get();

        foreach ($registrasi as $row) {
            $array = array(
                'tanggal' => $row['tglReg'],
                'noreg' => $row['noreg'],
                'dokter' => $row['dokter']['name'],
                'ruangan' => $row['ruang_perawatan']['name'],
                'jns_pembayaran' => $row['golpas']['name'],
                'jns_perawatan' => $row['id_jns_perawatan'],
                'increment' => (int)str_replace('-','',$row['tglReg'])
            );
            array_push($data, $array);
        }

        $rawatJalan = Zbilling_kasir_jalan::where('norekmed', $norekmed)->select(['tglmasuk as tanggal','noreg','dokterkt as dokter','polikt as ruangan','lunas as jns_pembayaran'])->get();
        foreach ($rawatJalan as $row) {
            $row['increment'] = (int)str_replace('-','',$row['tanggal']);
            $row['jns_perawatan'] = 'rj';
            array_push($data, $row);
        }

        $rawatInap = Zbilling_kasir_inap::where('norekmed', $norekmed)->select(['tglmasuk as tanggal','noreg','dokterkt as dokter','ruang as ruangan','lunas as jns_pembayaran'])->get();
        foreach ($rawatInap as $row) {
            $row['increment'] = (int)str_replace('-','',$row['tanggal']);
            $row['jns_perawatan'] = 'ri';
            array_push($data, $row);
        }

        $sort = array();
        foreach ($data as $key => $row)
        {
            $sort[$key] = $row['increment'];
        }

        array_multisort($sort, SORT_DESC, $data);

        return LibApp::response(200, $data);
    }
}
