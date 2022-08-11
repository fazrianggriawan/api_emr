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


$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Master Data
$router->get('master/rs', 'Master\MasterController@Rs');
$router->get('master/awalan_nama', 'Master\MasterController@AwalanNama');
$router->get('master/negara', 'Master\MasterController@Negara');
$router->get('master/provinsi', 'Master\MasterController@Provinsi');
// $router->get('master/kota/id_provinsi/{idProvinsi}', 'Master\MasterController@Kota');
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
$router->get('master/poliklinik', 'Master\MasterController@Poliklinik');
$router->get('master/dokter', 'Master\MasterController@Dokter');
$router->get('master/dokterByPoli/{idRuangan}', 'Master\MasterController@DokterByPoli');
$router->get('master/jnsPerawatan', 'Master\MasterController@JenisPerawatan');
$router->get('master/waktuPelayanan', 'Master\MasterController@WaktuPelayanan');
$router->get('master/ruangRawatInap', 'Master\MasterController@RuangRawatInap');
$router->get('master/kelasRuangan', 'Master\MasterController@KelasRuangan');

// Registrasi
$router->post('registrasi/save', 'Registrasi\RegistrasiController@SaveRegistrasi');
$router->get('registrasi/dataRegistrasi', 'Registrasi\RegistrasiController@GetDataRegistrasi');

// Tarif
$router->get('tarif/byCategory/{categoryId}', 'Billing\TarifController@TarifByCategory');
$router->get('tarif/category', 'Billing\TarifController@Category');
$router->get('tarif/jasa/{idTarifHarga}', 'Billing\TarifController@TarifJasa');
$router->post('tarif/defaultPelaksana', 'Billing\TarifController@DefaultPelaksana');

// Pasien
$router->post('pasien/save', 'PasienController@Save');
$router->post('pasien/update', 'PasienController@Update');
$router->post('pasien/filtering', 'PasienController@Filtering');
$router->get('pasien/getPasien/norm/{norm}', 'PasienController@GetPasien');
$router->get('pasien/searchBy/{searchBy}/key/{key}', 'PasienController@SearchBy');
$router->get('pasien/allData', 'PasienController@AllData');

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

// Upload File
$router->get('upload/getFiles/idPeserta/{idPeserta}', 'UploadController@getFileUploaded');
$router->get('upload/getImage/{filename}', 'UploadController@getImage');
$router->post('upload', 'UploadController@doUpload');
$router->post('upload/delete/image', 'UploadController@deleteImage');

// Login
$router->post('do_login', 'LoginController@doLogin');

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
// $router->get('master_lab', 'LabController@getAllMaster');
// $router->get('master_lab_cito', 'LabController@getAllMasterCito');
// $router->get('master_rad', 'RadiologiController@allData');
// $router->get('master_rad_detail', 'RadiologiController@getAllMasterDetail');
// $router->get('master_pat_anatomi', 'PatAnatomiController@getAllMaster');

// $router->get('icd10', 'IcdController@Icd10');
// $router->get('icd9', 'IcdController@Icd9');
// $router->get('sig_template', 'FarmasiController@getSigTemplate');
//$router->get('print_farmasi', 'PrintController@farmasi');
// $router->get('print_lab', 'PrintController@laboratorium');
// $router->get('print_rad', 'PrintController@radiologi');
// $router->get('print_tcpdf', 'PrintController@tcpdf');
// $router->get('get_test_order', 'TestOrderController@getData');
// $router->get('get_objective', 'ObjectiveController@getData');
// $router->get('get_assessment_umum', 'AssessmentUmumController@getData');
// $router->get('get_image', 'AssessmentUmumController@getImage');

// $router->post('registrasi', 'RegistrasiController@getDataRegistrasi');
// $router->post('cppt', 'CpptController@save');
// $router->post('aa', 'LoginController@aa');
// $router->post('save_sig_template', 'FarmasiController@saveSigTemplate');
// $router->post('save_diagnosa', 'DiagnosaController@save');
// $router->post('save_tindakan', 'TindakanController@save');
// $router->post('save_test_order', 'TestOrderController@save');
// $router->post('save_assessment_umum', 'AssessmentUmumController@save');
// $router->post('save_objective', 'ObjectiveController@save');
