<?php

namespace App\Http\Controllers;

use App\Http\Libraries\LibApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PasienController extends Controller
{

    public function Save(Request $request)
    {
        $insert = $this->SetDataPasien($request);
        $insert['date_created'] = date('Y-m-d h:i:s');
        $pasien = DB::table('pasien')->insert($insert);
    }

    public function Update(Request $request)
    {
        $update = $this->SetDataPasien($request);
        $pasien = DB::table('pasien')->where('id', $request->input('id'))->update($update);

    }

    public function SetDataPasien($request)
    {
        $data = array(
            'norm' => $request->input('nomorRm'),
            'rs' => $request->input('rs'),
            'awalan' => $request->input('awalanNama'),
            'nama' => $request->input('nama'),
            'tmpt_lahir' => $request->input('tempatLahir'),
            'tgl_lahir' => $request->input('tglLahir'),
            'jns_kelamin' => $request->input('jnsKelamin'),
            'alamat' => $request->input('alamat'),
            'negara' => $request->input('negara'),
            'provinsi' => $request->input('provinsi'),
            'kota' => $request->input('kota'),
            'kecamatan' => $request->input('kecamatan'),
            'kelurahan' => $request->input('kelurahan'),
            'suku' => $request->input('suku'),
            'status_nikah' => $request->input('statusNikah'),
            'agama' => $request->input('agama'),
            'tlp' => $request->input('tlpPasien'),
            'tlp_keluarga' => $request->input('tlpKeluarga'),
            'pekerjaan' => $request->input('pekerjaan'),
            'pendidikan' => $request->input('pendidikan'),
            'gol_darah' => $request->input('golonganDarah'),
            'nik' => $request->input('nik'),
            'nrp_nip' => $request->input('nrpNip'),
            'angkatan' => $request->input('angkatan'),
            'pangkat' => $request->input('pangkat'),
            'kesatuan' => $request->input('kesatuan'),
            'jabatan' => $request->input('jabatan'),
            'group_pasien' => $request->input('groupPasien'),
            'gol_pasien' => $request->input('golonganPasien'),
            'no_asuransi' => $request->input('nomorAsuransi')
        );

        return $data;
    }

    public function GetPasien($norm)
    {
        $pasien = DB::table('pasien')->where('norm', $norm)->get();

        if( count($pasien) > 0 )
            return LibApp::response_success($pasien);
        else
            return LibApp::response(201, [], 'Data Tidak Ditemukan.');
    }

    public function Filtering(Request $request)
    {
        $where = array();
        if( $request->input('norm') ) $where[] = ['norm', '=', $request->input('norm')];
        if( $request->input('nama') ) $where[] = ['nama', 'like', '%'.$request->input('nama').'%'];
        if( $request->input('alamat') ) $where[] = ['alamat', 'like', '%'.$request->input('alamat').'%'];
        if( $request->input('noAsuransi') ) $where[] = ['no_asuransi', 'like', '%'.$request->input('noAsuransi').'%'];
        if( $request->input('tglLahir') ) $where[] = ['tgl_lahir', '=', $request->input('tglLahir')];
        if( $request->input('tlp') ) $where[] = ['tlp', '=', $request->input('tlp')];

        if( count($where) > 0 )
            $pasien = DB::table('pasien')->where($where)->get();
        else
            $pasien = [];

        return LibApp::response_success($pasien);

    }

}
