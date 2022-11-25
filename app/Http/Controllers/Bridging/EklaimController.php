<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Libraries\LibEklaim;
use App\Models\Registrasi;
use App\Models\Registrasi_pulang_perawatan;
use Illuminate\Http\Client\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class EklaimController extends BaseController
{

    public function NewKlaim(Request $request)
    {
        $registrasi = Registrasi::GetAllData()->where('noreg', $request->noreg);

        $data = array(
            'metadata' => array(
                'method' => 'new_claim'
            ),
            'data' => array(
                'nomor_kartu' => $registrasi->sep->peserta->nokartu,
                'nomor_sep' => $registrasi->sep->nosep,
                'nomor_rm' => $registrasi->sep->peserta->noMR,
                'nama_pasien' => $registrasi->sep->peserta->nama,
                'tgl_lahir' => $registrasi->sep->peserta->tgl_lahir.' 02:00:00',
                'gender' => ($registrasi->pasien->jns_kelamin == 'L') ? '1' : '2'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function UpdateDataPasien(Request $request)
    {

        $registrasi = Registrasi::GetAllData()->where('noreg', $request->noreg);

        $data = array(
            'metadata' => array(
                'method' => 'update_patient',
                'nomor_rm' => $registrasi->pasien->norm
            ),
            'data' => array(
                'nomor_kartu' => $registrasi->sep->peserta->nokartu,
                'nomor_sep' => $registrasi->sep->nosep,
                'nomor_rm' => $registrasi->sep->peserta->noMR,
                'nama_pasien' => $registrasi->sep->peserta->nama,
                'tgl_lahir' => $registrasi->sep->peserta->tgl_lahir.' 00:00:00',
                'gender' => ($registrasi->pasien->jns_kelamin == 'L') ? '1' : '2'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function HapusDataPasien(Request $request)
    {
        $registrasi = Registrasi::GetAllData()->where('noreg', $request->noreg);

        $data = array(
            'metadata' => array(
                'method' => 'delete_patient'
            ),
            'data' => array(
                'nomor_rm' => $registrasi->pasien->norm,
                'coder_nik' => self::Coder()
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function SetDataKlaim(Request $request)
    {
        $registrasi = Registrasi::GetAllData()->where('noreg', $request->noreg);

        return $tglPulang = self::GetTanggalPUlang($registrasi);

        $data = array(
            'metadata' => array(
                'method' => 'set_claim_data',
                'nomor_sep' => $registrasi->sep->no_sep
            ),
            'data' => array(
                'nomor_sep' => $registrasi->sep->no_sep,
                'nomor_kartu' => $registrasi->sep->peserta->noKartu,
                'tgl_masuk' => $registrasi->dateCreated,
                'tgl_pulang' => '2017-12-01 09:55:00',
                'jenis_rawat' => ($registrasi->id_jns_perawatan == 'ri') ? '1' : '2',
                'kelas_rawat' => '1',
                'adl_sub_acute' => '', //'15',
                'adl_chronic' => '', //'12',
                'icu_indikator' => '', //'1', jika pasien masuk icu diisi '1'
                'icu_los' => '', //'2', // jumlah hari rawat icu
                'ventilator_hour' => '', // '5', jam pemakaian ventilator di ICU
                'upgrade_class_ind' => '', // '1', nilai '1' jika ada naik kelas dan '0' jika tidak naik kelas
                'upgrade_class_class' => '', // 'vip', naik ke kelas
                'upgrade_class_los' => 0, // '5', lama hari rawat naik kelas
                'add_payment_pct' => '', //'35',
                'birth_weight' => '0',
                'discharge_status' => '1',
                'diagnosa' => 'S71.0#A00.1',
                'procedure' => '81.52#88.38#86.22',
                'diagnosa_inagrouper' => 'S71.0#A00.1',
                'procedure_inagrouper' => '81.52#88.38#86.22+3#86.22',
                'tarif_rs' => array(
                    'prosedur_non_bedah' =>'300000',
                    'prosedur_bedah' =>'20000000',
                    'konsultasi' =>'300000',
                    'tenaga_ahli' =>'200000',
                    'keperawatan' =>'80000',
                    'penunjang' =>'1000000',
                    'radiologi' =>'500000',
                    'laboratorium' =>'600000',
                    'pelayanan_darah' =>'150000',
                    'rehabilitasi' =>'100000',
                    'kamar' =>'6000000',
                    'rawat_intensif' => '2500000',
                    'obat' => '100000',
                    'obat_kronis' => '1000000',
                    'obat_kemoterapi' => '5000000',
                    'alkes' => '500000',
                    'bmhp' => '400000',
                    'sewa_alat' => '210000'
                ),
                'pemulasaraan_jenazah' => '1',
                'kantong_jenazah' => '1',
                'peti_jenazah' => '1',
                'plastik_erat' => '1',
                'desinfektan_jenazah' => '1',
                'mobil_jenazah' => '0',
                'desinfektan_mobil_jenazah' => '0',
                'covid19_status_cd' => '1',
                'nomor_kartu_t' => 'nik',
                'episodes' => '1;12#2;3#6;5',
                'covid19_cc_ind' => '1',
                'covid19_rs_darurat_ind' => '1',
                'covid19_co_insidense_ind' => '1',
                'covid19_penunjang_pengurang' => array(
                    'lab_asam_laktat'  => '1',
                    'lab_procalcitonin'  => '1',
                    'lab_crp'  => '1',
                    'lab_kultur'  => '1',
                    'lab_d_dimer'  => '1',
                    'lab_pt'  => '1',
                    'lab_aptt'  => '1',
                    'lab_waktu_pendarahan'  => '1',
                    'lab_anti_hiv'  => '1',
                    'lab_analisa_gas'  => '1',
                    'lab_albumin'  => '1',
                    'rad_thorax_ap_pa'  => '0'
                ),
                'terapi_konvalesen' => '1000000',
                'akses_naat' => 'C',
                'isoman_ind' => '0',
                'bayi_lahir_status_cd' => 1,
                'tarif_poli_eks' => '100000',
                'nama_dokter' => 'RUDY, DR',
                'kode_tarif' => 'AP',
                'payor_id' => '3',
                'payor_cd' => 'JKN',
                'cob_cd' => '0001',
                'coder_nik' => self::Coder()
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function UpdateProcedure(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'set_claim_data',
                'nomor_sep' => '0001R0016120666662',
            ),
            'data' => array(
                'procedure' => '36.06#88.09',
                'coder_nik' => self::Coder()
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function HapusProcedure(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'set_claim_data',
                'nomor_sep' => '0001R0016120666662',
            ),
            'data' => array(
                'procedure' => '#',
                'coder_nik' => self::Coder()
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function GroupingStage1(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'grouper',
                'stage' => '1'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function GroupingStage2(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'grouper',
                'stage' => '2'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662',
                'special_cmg' => 'RR04#YY01'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function FinalisasiKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'claim_final'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662',
                'coder_nik' => '123123123123'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function ReEditKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'reedit_claim'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function SendKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'send_claim'
            ),
            'data' => array(
                'start_dt' => '2016-01-07',
                'stop_dt' => '2016-01-07',
                'jenis_rawat' => '1',
                'date_type' => '2'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function SendKlaimSatuan(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'send_claim_individual'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function DetailKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'get_claim_data'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function StatusKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'get_claim_status'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function DeleteKlaim(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'delete_claim'
            ),
            'data' => array(
                'nomor_sep' => '0001R0016120666662',
                'coder_nik' => self::Coder()
            )
        );
        return LibEklaim::exec(json_encode($data));
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
        return LibEklaim::exec(json_encode($data));
    }

    public function CariProsedur(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'search_procedures'
            ),
            'data' => array(
                'keyword' => '74.9'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function UploadFile(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'file_upload',
                'nomor_sep' => '0000005ICC20040001',
                'file_class' => 'resume_medis',
                'file_name' => 'resumse.pdf',
            ),
            'data' => '... base64_encoded binary file ...'
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function HapusFileUpload(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'file_delete'
            ),
            'data' => array(
                'nomor_sep' => '0000005ICC20040001',
                'file_id' => '1'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function GetUploadFile(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'file_get'
            ),
            'data' => array(
                'nomor_sep' => '0000005ICC20040001'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function CheckStatusKlaimCovid(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'retrieve_claim_status'
            ),
            'data' => array(
                'nomor_sep' => '0000005ICC20040018',
                'nomor_pengajuan' => '0000005R47010601'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function CariDiagnosaInaGrouper(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'search_diagnosis_inagrouper'
            ),
            'data' => array(
                'keyword' => 'J44'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public function CariProsedurInaGrouper(Request $request)
    {
        $data = array(
            'metadata' => array(
                'method' => 'search_procedures_inagrouper'
            ),
            'data' => array(
                'keyword' => '74.9'
            )
        );
        return LibEklaim::exec(json_encode($data));
    }

    public static function Coder()
    {
        return '3201150710820010';
    }

    public static function GetTanggalPulang($registrasi)
    {
        if( $registrasi->id_jns_perawatan == 'ri' ){
            $registrasiPulang = Registrasi_pulang_perawatan::with(['r_registrasi'])->where('noreg', $registrasi->noreg)->get();
            $countArray = count($registrasiPulang);
            if( $countArray > 0 ){
                $key = $countArray--;
                return $registrasiPulang[$key]['tanggal'];
            }
        }else{
            return $registrasi->tglReg;
        }
    }

}
