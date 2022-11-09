<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use Illuminate\Support\Facades\Storage;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Master Data
$router->get('master/rs', 'Master\MasterController@Rs');
$router->get('master/awalan_nama', 'Master\MasterController@AwalanNama');
$router->get('master/negara', 'Master\MasterController@Negara');
$router->get('master/provinsi', 'Master\MasterController@Provinsi');
$router->get('master/kota/id_provinsi/{idProvinsi}', 'Master\MasterController@Kota');
$router->post('master/kota', 'Master\MasterController@Kota');
$router->get('master/kecamatan/id_kota/{idKota}', 'Master\MasterController@Kecamatan');
$router->get('master/kelurahan/id_kecamatan/{idKecamatan}', 'Master\MasterController@Kelurahan');
$router->get('master/suku', 'Master\MasterController@Suku');
$router->get('master/status_nikah', 'Master\MasterController@StatusNikah');
$router->get('master/agama', 'Master\MasterController@Agama');
$router->get('master/pekerjaan', 'Master\MasterController@Pekerjaan');
$router->get('master/pendidikan', 'Master\MasterController@Pendidikan');
$router->get('master/angkatan', 'Master\MasterController@Angkatan');
$router->get('master/pangkat', 'Master\MasterController@Pangkat');
$router->get('master/group_pasien', 'Master\MasterController@GroupPasien');
$router->get('master/golongan_pasien/id_grouppasien/{idGroupPasien}', 'Master\MasterController@GolonganPasien');
$router->get('master/dokter', 'Master\MasterController@Dokter');
$router->get('master/dokterByPoli/{idRuangan}', 'Master\MasterController@DokterByPoli');
$router->get('master/jnsPerawatan', 'Master\MasterController@JenisPerawatan');
$router->get('master/waktuPelayanan', 'Master\MasterController@WaktuPelayanan');
$router->get('master/ruangan/{jnsPerawatan}', 'Master\MasterController@Ruangan');
$router->get('master/kelasRuangan', 'Master\MasterController@Kelas');
$router->get('master/tempatTidurByRuangan/{idRuangan}', 'Master\MasterController@TempatTidurByRuangan');
$router->get('master/jnsPembayaran', 'Master\MasterController@JnsPembayaran');

// Registrasi
$router->get('registrasi/dataRegistrasi', 'Registrasi\RegistrasiController@GetDataRegistrasi');
$router->get('registrasi/registrasiByNoreg/{noreg}', 'Registrasi\RegistrasiController@GetRegistasi');
$router->get('registrasi/riwayatKunjungan/{idPasien}/{norm}', 'Registrasi\RiwayatController@GetData');
$router->post('registrasi/save', 'Registrasi\RegistrasiController@SaveRegistrasi');
$router->post('registrasi/filtering', 'Registrasi\RegistrasiController@FilterDataRegistrasi');
$router->post('registrasi/request_rm', 'Registrasi\PermintaanRmController@GetData');

// Tarif
$router->get('tarif/byCategory/{categoryId}', 'Billing\TarifController@TarifByCategory');
$router->get('tarif/category', 'Billing\TarifController@Category');
$router->get('tarif/jasa/{idTarifHarga}/{noreg}/{ruangan}', 'Billing\TarifController@TarifJasa');
$router->post('tarif/cariTarif', 'Billing\TarifController@CariTarif');
$router->post('tarif/defaultPelaksana', 'Billing\TarifController@DefaultPelaksana');

// Billing
$router->post('billing/save', 'Billing\BillingController@Save');
$router->post('billing/delete', 'Billing\BillingController@Delete');
$router->post('billing/updateJumlah', 'Billing\BillingController@UpdateJumlah');
$router->post('billing/addDiscount', 'Billing\BillingController@AddDiscount');
$router->post('billing/addPembayaran', 'Billing\BillingController@AddPembayaran');
$router->post('billing/deletePembayaran', 'Billing\BillingController@DeletePembayaran');
$router->post('billing/deleteBilling', 'Billing\BillingController@DeleteBilling');
$router->get('billing/billingByNoreg/{noreg}/{status}', 'Billing\BillingController@BillingByNoreg');
$router->get('billing/dataPembayaran/{noreg}', 'Billing\BillingController@DataPembayaran');
$router->get('billing/billingDetailByUnit/{noreg}/{unit}', 'Billing\BillingController@BillingByUnit');
$router->get('billing/billingByHead/{idBillingHead}', 'Billing\BillingController@BillingByHead');

// Pasien
$router->post('pasien/save', 'Pasien\PasienController@Save');
$router->post('pasien/update', 'Pasien\PasienController@Update');
$router->post('pasien/filtering', 'Pasien\PasienController@Filtering');
$router->get('pasien/getPasien/norm/{norm}', 'Pasien\PasienController@GetPasien');
$router->get('pasien/searchBy/{searchBy}/key/{key}', 'Pasien\PasienController@SearchBy');
$router->get('pasien/allData', 'Pasien\PasienController@AllData');

// Rikkes
$router->get('rikkes/dataPeserta', 'RikkesController@GetDataPeserta');
$router->get('rikkes/getData/idPeserta/{idPeserta}', 'RikkesController@GetDataRikkes');
$router->get('rikkes/peserta/noUrut/{noUrut}', 'RikkesController@GetPesertaByNoUrut');
$router->get('rikkes/printSticker/noUrut/{noUrut}', 'RikkesController@PrintSticker');
$router->get('rikkes/printStickerAllPeserta/dari/{noUrutFrom}/sampai/{noUrutTo}', 'RikkesController@PrintStickerAllPeserta');
$router->get('rikkes/export', 'ExcelController@Export');
$router->get('rikkes/exportAllData', 'ExcelController@ExportAllData');
$router->get('rikkes/getHasilLab/idPeserta/{idPeserta}', 'RikkesController@GetHasilLab');
$router->get('rikkes/getHasilLabKeterangan/idPeserta/{idPeserta}', 'RikkesController@GetHasilLabKeterangan');
$router->get('rikkes/printHasilLab/idPeserta/{idPeserta}', 'RikkesController@PrintHasilLab');
$router->get('rikkes/getHasilRadiologi/idPeserta/{idPeserta}', 'RikkesController@GetHasilRadiologi');
$router->get('rikkes/printHasilRadiologi/idPeserta/{idPeserta}', 'RikkesController@PrintHasilRadiologi');
$router->get('rikkes/getHasilPsikometri/noUrut/{noUrut}', 'RikkesController@GetHasilPsikometri');
$router->get('rikkes/getHasilEkg/noUrut/{noUrut}', 'RikkesController@GetHasilEkg');
$router->post('rikkes/save', 'RikkesController@Save');
$router->post('rikkes/save/hasilRadiologi', 'RikkesController@SaveHasilRadiologi');
$router->post('rikkes/save/hasilLab', 'RikkesController@SaveHasilLab');
$router->get('rikkes/debug/idPeserta/{idPeserta}', 'RikkesController@debug');

// EMR
$router->post('emr/saveOrder', 'Emr\TestOrderController@SaveOrder');
$router->post('emr/deleteOrder', 'Emr\TestOrderController@DeleteOrder');
$router->get('emr/dataOrder/{noreg}/{unit}', 'Emr\TestOrderController@OrderByNoreg');

$router->get('emr/question', 'Emr\QuestionController@Question');
$router->get('emr/controltype', 'Emr\QuestionController@ControlType');
$router->get('emr/parent/{id_form}', 'Emr\QuestionController@ParentByForm');
$router->post('emr/save/emr-form-ruangan', 'Emr\QuestionController@SaveEmrFormRuangan');

// Radiologi
$router->get('radiologi/dataOrder/{tanggal}/{status}', 'Radiologi\RadiologiController@DataOrder');
$router->get('radiologi/hasil-photo/{noreg}', 'Radiologi\RadiologiController@HasilPhoto');
$router->get('radiologi/cariTindakan/{keyword}', 'Radiologi\RadiologiController@CariTindakan');

// Laboratorium
$router->get('lab/nilaiRujukan/{group}/{noreg}', 'Lab\HasilLabController@DataNilaiRujukan');
$router->get('lab/hasil/{idBillingHead}', 'Lab\HasilLabController@DataNilaiRujukan');
$router->get('lab/cariPemeriksaan/{keyword}', 'Lab\LaboratoriumController@CariPemeriksaan');
$router->post('lab/saveHasil', 'Lab\HasilLabController@SaveHasil');
$router->post('lab/savePemeriksaan', 'Lab\LaboratoriumController@SavePemeriksaan');

// Farmasi
$router->get('farmasi/dataObat', 'Farmasi\ObatController@DataObat');
$router->get('farmasi/cariObat/{key}', 'Farmasi\ObatController@CariObat');
$router->get('farmasi/getBilling/{noreg}/{status}', 'Farmasi\BillingController@GetBilling');
$router->get('farmasi/getDataPembayaran/{noreg}', 'Farmasi\BillingController@GetDataPembayaran');
$router->get('farmasi/master/depo', 'Farmasi\MasterController@Depo');
$router->get('farmasi/master/supplier', 'Farmasi\MasterController@Supplier');
$router->get('farmasi/opname/periode', 'Farmasi\OpnameController@DataPeriode');
$router->get('farmasi/opname/stok-obat/{idPeriode}', 'Farmasi\OpnameController@DataStokObat');
$router->post('farmasi/saveBilling', 'Farmasi\BillingController@SaveBilling');
$router->post('farmasi/savePembayaran', 'Farmasi\BillingController@SavePembayaran');

// Upload File
$router->get('upload/getFiles/idPeserta/{idPeserta}', 'UploadController@getFileUploaded');
$router->get('upload/getImage/{filename}', 'UploadController@getImage');
$router->post('upload', 'UploadController@doUpload');
$router->post('upload/delete/image', 'UploadController@deleteImage');

// Login
$router->post('do_login', 'LoginController@DoLogin');
$router->post('role_access', 'LoginController@RoleAccess');

// Print
$router->get('print/stickerBarcode/{idPasien}', 'Printer\Barcode@GoPrint');
$router->get('print/biodataPasien/{idPasien}', 'Printer\BiodataPasien@GoPrint');
$router->get('print/dataRegistrasi/{noreg}', 'Printer\Registrasi@DataRegistrasi');
$router->get('print/kwitansi/{noKwitansi}', 'Printer\Kwitansi@GoPrint');
$router->get('print/rincianBilling/{noreg}', 'Printer\RincianBilling@GoPrint');
$router->get('print/requestRm/{id_request}', 'Printer\RequestRm@GoPrint');
$router->get('print/billingFarmasi/{noreg}', 'Printer\BillingFarmasi@GoPrint');
$router->get('print/hasilLab/{noreg}', 'Printer\HasilLab@GoPrint');

$router->get('test', 'LoginController@Test');

// App
$router->get('app/modules/{username}', 'App\AppController@ModuleByUsername');
$router->get('app/ruanganByUsername/{username}', 'App\AppController@RuanganByUsername');

// Setting
$router->get('setting/emr/form-ruangan', 'Setting\Emr\FormRuanganController@FormRuangan');

// Medical Record
// $router->get('medicalRecord', 'Billing\TarifController@hallo');


// Module
// $router->get('modules/{username}', 'ModulesController@Modules');
// $router->get('modules/submenu/module/{module}/username/{username}', 'ModulesController@Submenu');

// // $router->get('master_poli', 'MasterController@poliklinik');
// $router->get('master_keluhan', 'MasterController@Keluhan');

// $router->get('master_obat', 'FarmasiController@getMasterObat');
// $router->get('master_dokter', 'DokterController@getAllDokter');
// $router->get('master_dokter_by_poli', 'DokterController@getAllDokterByPoli');
$router->get('master_lab', 'MedicalRecord\LabController@getAllMaster');
// $router->get('master_lab_cito', 'LabController@getAllMasterCito');
$router->get('master_rad', 'Radiologi\RadiologiController@getAllMaster');
// $router->get('master_rad_detail', 'RadiologiController@getAllMasterDetail');
// $router->get('master_pat_anatomi', 'PatAnatomiController@getAllMaster');

$router->get('icd10', 'MedicalRecord\IcdController@Icd10');
$router->get('icd9', 'MedicalRecord\IcdController@Icd9');
// $router->get('sig_template', 'FarmasiController@getSigTemplate');
//$router->get('print_farmasi', 'PrintController@farmasi');
// $router->get('print_lab', 'PrintController@laboratorium');
// $router->get('print_rad', 'PrintController@radiologi');
// $router->get('print_tcpdf', 'PrintController@tcpdf');
// $router->get('get_test_order', 'TestOrderController@getData');
// $router->get('get_objective', 'ObjectiveController@getData');
// $router->get('get_assessment_umum', 'AssessmentUmumController@getData');
$router->get('get_image', 'MedicalRecord\AssessmentUmumController@getImage');

// $router->post('registrasi', 'RegistrasiController@getDataRegistrasi');
// $router->post('cppt', 'CpptController@save');
// $router->post('aa', 'LoginController@aa');
// $router->post('save_sig_template', 'FarmasiController@saveSigTemplate');
// $router->post('save_diagnosa', 'DiagnosaController@save');
// $router->post('save_tindakan', 'TindakanController@save');
// $router->post('save_test_order', 'TestOrderController@save');
// $router->post('save_assessment_umum', 'AssessmentUmumController@save');
// $router->post('save_objective', 'ObjectiveController@save');

$router->get('eklaim/print', 'Bridging\EklaimController@PrintKlaim');


$router->get('test', function(){
    Storage::disk('local')->put('example.txt', 'Contents');
});