<?php

namespace App\Http\Controllers\Lab;

use App\Http\Libraries\LibApp;
use App\Models\Lab_hasil_pemeriksaan;
use App\Models\Lab_nama_hasil_rujukan;
use App\Models\Lab_nilai_rujukan_options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Lumen\Routing\Controller as BaseController;

class HasilLabController extends BaseController
{
    public function DataNilaiRujukan($group, $noreg)
    {
        $this->arrayGroup = array($group, 'ALL');
        if( strtoupper($group) == 'P' || strtoupper($group) == 'L' ){
            $this->arrayGroup[] = 'LP';
        }

        $data = Lab_nama_hasil_rujukan::with(['r_nama_hasil','r_nilai_rujukan'])->whereHas('r_nilai_rujukan', function($q){
            return $q->whereIn('group', $this->arrayGroup);
        })->get();

        $res = array();
        foreach ($data as $row ) {
            $hasil = Lab_hasil_pemeriksaan::where('noreg', $noreg)->where('id_lab_nama_hasil_rujukan', $row->id)->where('active', 1)->first();
            $a = array(
                'id' => $row->id,
                'name' => $row->r_nama_hasil->name,
                'hasil' => ($hasil) ? $hasil->hasil : '',
                'nilaiRujukan' => $row->r_nilai_rujukan->name,
                'group' => $row->r_nama_hasil->category,
                'options' => Lab_nilai_rujukan_options::where('id_lab_nama_hasil', $row->r_nama_hasil->id)->get()
            );
            array_push($res, $a);
        }
        return LibApp::response(200, $res);
    }

    public function SaveHasil(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            foreach ($request->data as $row) {
                $hasil = Lab_hasil_pemeriksaan::where('noreg', $request->noreg)->where('id_lab_nama_hasil_rujukan', $row['id'])->where('active', 1)->first();
                if( $hasil ){
                    Lab_hasil_pemeriksaan::where('id', $hasil->id)->update(['active' => 0]);
                    if( $row['hasil'] ){
                        $data = new Lab_hasil_pemeriksaan();
                        $data->id_lab_nama_hasil_rujukan = $row['id'];
                        $data->hasil = $row['hasil'];
                        $data->noreg = $request->noreg;
                        $data->save();
                    }
                }else{
                    if( $row['hasil'] ){
                        $data = new Lab_hasil_pemeriksaan();
                        $data->id_lab_nama_hasil_rujukan = $row['id'];
                        $data->hasil = $row['hasil'];
                        $data->noreg = $request->noreg;
                        $data->save();
                    }
                }
            }
            DB::commit();
            return LibApp::response(200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], $th->getMessage());
        }
        return $request->data;
    }

}
