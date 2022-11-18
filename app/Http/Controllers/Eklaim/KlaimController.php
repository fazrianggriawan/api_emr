<?php

namespace App\Http\Controllers\Eklaim;

use App\Http\Libraries\LibApp;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Http\Libraries\LibEklaim;
use App\Models\Billing_detail;
use App\Models\Farmasi_billing;
use App\Models\Registrasi;
use App\Models\Registrasi_sep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KlaimController extends BaseController
{
    public function NewKlaim(Request $request)
    {
        try {
            $data = array(
                'metadata' => array(
                    'method' => 'new_claim'
                ),
                'data' => array(
                    'nomor_kartu' => $request->sep['peserta']['noKartu'],
                    'nomor_sep' => $request->sep['noSep'],
                    'nomor_rm' => $request->registrasi['pasien']['norm'],
                    'nama_pasien' => $request->registrasi['pasien']['nama'],
                    'tgl_lahir' => $request->registrasi['pasien']['tgl_lahir'].' 00:00:00',
                    'gender' => ($request->registrasi['pasien']['jns_kelamin'] == 'P') ? '1' : '2'
                )
            );

            return $this->HandleResponse(LibEklaim::exec(json_encode($data)));

        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function BillingGroupByEklaim($noreg)
    {
        try {
            $billing = Billing_detail::with(['r_tarif_harga'=>function($q){
                            return $q->with(['r_tarif'=>function($q){
                                return $q->with(['r_tarif_category'=>function($q){
                                    return $q->with(['r_eklaim_group_tarif']);
                                }]);
                            }]);
                        }])
                        ->where('noreg', $noreg)
                        ->where('active', 1)
                        ->get();

            $data = collect($billing)->groupBy('r_tarif_harga.r_tarif.r_tarif_category.r_eklaim_group_tarif.id_eklaim_group_tarif');

            $farmasi = Farmasi_billing::select(DB::raw('COALESCE(SUM(harga * qty), 0) AS total'))->where('active', 1)->where('noreg', $noreg)->first();

            $total = 0;
            $array = array();
            foreach ($data as $key => $value) {
                $total = 0;
                $array[$key] = array();

                foreach($value as $row){
                    $total += $row->r_tarif_harga->harga * $row->qty;
                }
                $array[$key] = $total;
            }

            if( $farmasi->total > 0 ){
                $array['obat'] = $farmasi->total;
            }

            return LibApp::response(200, $array);

        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function SaveKlaim(Request $request)
    {

        try {
            $diagnosa = $this->ParsingICD($request->diagnosa);
            $prosedur = $this->ParsingICD($request->prosedur);

            $data = array(
                'metadata' => array(
                    'method' => 'set_claim_data',
                    'nomor_sep' => $request->sep['noSep']
                ),
                'data' => array(
                    'nomor_sep' => $request->sep['noSep'],
                    'nomor_kartu' => $request->sep['peserta']['noKartu'],
                    'tgl_masuk' => $request->registrasi['dateCreated'],
                    'tgl_pulang' => $request->registrasi['dateCreated'],
                    'jenis_rawat' => ($request->registrasi['id_jns_perawatan'] == 'ri') ? '1' : '2',
                    'kelas_rawat' => $request->sep['klsRawat']['klsRawatHak'],
                    'adl_sub_acute' => '', //'15',
                    'adl_chronic' => '', //'12',
                    'icu_indikator' => '', //'1', jika pasien masuk icu diisi '1'
                    'icu_los' => '', //'2', // jumlah hari rawat icu
                    'ventilator_hour' => '', // '5', jam pemakaian ventilator di ICU
                    'upgrade_class_ind' => '', // '1', nilai '1' jika ada naik kelas dan '0' jika tidak naik kelas
                    'upgrade_class_class' => '', // 'vip', naik ke kelas
                    'upgrade_class_los' => 0, // '5', lama hari rawat naik kelas
                    'add_payment_pct' => '', //'35',
                    'birth_weight' => '',
                    'discharge_status' => '',
                    'diagnosa' => $diagnosa,
                    'procedure' => $prosedur,
                    'diagnosa_inagrouper' => '',
                    'procedure_inagrouper' => '',
                    'tarif_rs' => array(
                        'prosedur_non_bedah'=> (isset($request->billing['prosedur_non_bedah'])) ? $request->billing['prosedur_non_bedah'] : '0',
                        'prosedur_bedah'    => (isset($request->billing['prosedur_bedah'])) ? $request->billing['prosedur_bedah'] : '0',
                        'konsultasi'        => (isset($request->billing['konsultasi'])) ? $request->billing['konsultasi'] : '0',
                        'tenaga_ahli'       => (isset($request->billing['tenaga_ahli'])) ? $request->billing['tenaga_ahli'] : '0',
                        'keperawatan'       => (isset($request->billing['keperawatan'])) ? $request->billing['keperawatan'] : '0',
                        'penunjang'         => (isset($request->billing['penunjang'])) ? $request->billing['penunjang'] : '0',
                        'radiologi'         => (isset($request->billing['radiologi'])) ? $request->billing['radiologi'] : '0',
                        'laboratorium'      => (isset($request->billing['laboratorium'])) ? $request->billing['laboratorium'] : '0',
                        'pelayanan_darah'   => (isset($request->billing['pelayanan_darah'])) ? $request->billing['pelayanan_darah'] : '0',
                        'rehabilitasi'      => (isset($request->billing['rehabilitasi'])) ? $request->billing['rehabilitasi'] : '0',
                        'kamar'             => (isset($request->billing['kamar'])) ? $request->billing['kamar'] : '0',
                        'rawat_intensif'    => (isset($request->billing['rawat_intensif'])) ? $request->billing['rawat_intensif'] : '0',
                        'obat'              => (isset($request->billing['obat'])) ? $request->billing['obat'] : '0',
                        'obat_kronis'       => (isset($request->billing['obat_kronis'])) ? $request->billing['obat_kronis'] : '0',
                        'obat_kemoterapi'   => (isset($request->billing['obat_kemoterapi'])) ? $request->billing['obat_kemoterapi'] : '0',
                        'alkes'             => (isset($request->billing['alkes'])) ? $request->billing['alkes'] : '0',
                        'bmhp'              => (isset($request->billing['bmhp'])) ? $request->billing['bmhp'] : '0',
                        'sewa_alat'         => (isset($request->billing['sewa_alat'])) ? $request->billing['sewa_alat'] : '0',
                    ),
                    'pemulasaraan_jenazah' => '',
                    'kantong_jenazah' => '',
                    'peti_jenazah' => '',
                    'plastik_erat' => '',
                    'desinfektan_jenazah' => '',
                    'mobil_jenazah' => '',
                    'desinfektan_mobil_jenazah' => '',
                    'covid19_status_cd' => '',
                    'nomor_kartu_t' => '',
                    'episodes' => '',
                    'covid19_cc_ind' => '',
                    'covid19_rs_darurat_ind' => '',
                    'covid19_co_insidense_ind' => '',
                    'covid19_penunjang_pengurang' => array(
                        'lab_asam_laktat'  => '',
                        'lab_procalcitonin'  => '',
                        'lab_crp'  => '',
                        'lab_kultur'  => '',
                        'lab_d_dimer'  => '',
                        'lab_pt'  => '',
                        'lab_aptt'  => '',
                        'lab_waktu_pendarahan'  => '',
                        'lab_anti_hiv'  => '',
                        'lab_analisa_gas'  => '',
                        'lab_albumin'  => '',
                        'rad_thorax_ap_pa'  => ''
                    ),
                    'terapi_konvalesen' => '',
                    'akses_naat' => '',
                    'isoman_ind' => '',
                    'bayi_lahir_status_cd' => 0,
                    'tarif_poli_eks' => '',
                    'nama_dokter' => $request->registrasi['dokter']['name'],
                    'kode_tarif' => 'CP',
                    'payor_id' => '00003',
                    'payor_cd' => 'JKN',
                    'cob_cd' => '',
                    'coder_nik' => '123123123123'
                )
            );

            return $this->HandleResponse(LibEklaim::exec(json_encode($data)));

        } catch (\Throwable $th) {
            //throw $th;
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public function PrintKlaim($noSep)
    {
        $data = array(
            'metadata' => array(
                'method' => 'claim_print'
            ),
            'data' => array(
                'nomor_sep' => $noSep
            )
        );

        return $this->GoPrint(LibEklaim::exec(json_encode($data)), $noSep);
    }

    public function EditUlang(Request $request)
    {
        try {
            $data = array(
                'metadata' => array(
                    'method' => 'reedit_claim'
                ),
                'data' => array(
                    'nomor_sep' => $request->noSep
                )
            );

            return $this->HandleResponse(LibEklaim::exec(json_encode($data)));
        } catch (\Throwable $th) {
            return LibApp::response(201, [], $th->getMessage());
        }
    }

    public static function GoPrint($res, $noSep)
    {
        $res = json_decode($res);

        if( $res->metadata->code != 200 ){
            return $res->metadata->message;
        }

        // variable data adalah base64 dari file pdf
        $pdf = base64_decode($res->data);
        // hasilnya adalah berupa binary string $pdf, untuk disimpan:
        $filename = "EKlaim-".$noSep.".pdf";
        // file_put_contents($filename, $pdf);
        // atau untuk ditampilkan dengan perintah:
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename=".$filename);
        echo $pdf;
        exit;
    }

    public static function ParsingICD($icd)
    {
        $string = '';
        foreach ($icd as $row) {
            $string .= '#'.$row['id'];
        }
        return ltrim($string, '#');
    }

    public static function HandleResponse($data)
    {
        $json = json_decode($data);

        if( $json->metadata->code == 200 ){
            return LibApp::response(200, $json, 'Proses Berhasil : '.$json->metadata->message);
        }else{
            return LibApp::response(201, [], 'Gagal Menyimpan : '.$json->metadata->message);
        }
    }

    public function UpdateSep(Request $request)
    {
        try {
            DB::beginTransaction();
            $checkIt = Registrasi_sep::where('noreg', $request->noreg)->first();
            if( isset($checkIt->id) ){
                Registrasi_sep::where('noreg', $request->noreg)->update(['no_sep'=>$request->sep]);
            }else{
                Registrasi_sep::SaveData($request->noreg, $request->sep);
            }

            DB::commit();

            return LibApp::response(200, ['noreg'=>$request->noreg], 'SEP Berhasil Disimpan');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return LibApp::response(201, [], 'Gagal Disimpan. '.$th->getMessage());
        }
    }

}
