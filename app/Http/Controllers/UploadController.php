<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\LibApp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadController extends BaseController
{
    public function doUpload(Request $request)
    {
        $destination_path = base_path().'/public/uploads/';
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $image = Image::make($file->getRealPath());
        $uploaded = $image->save($destination_path.$name);

        if( $uploaded ){
            $res = array(
                'id_rikkes_peserta' => $request->input('idPeserta'),
                'file_location' => 'public/uploads/'.$name,
                'filename' => $name,
                'type' => $file->getMimeType(),
                'size' => round($file->getSize() / 1024 / 1024, 2).' Mb',
                'dateCreated' => date('Y-m-d H:i:s')
            );
            DB::table('rikkes_fileupload')->insert($res);
            return LibApp::response_success($res);
        }else{
            return LibApp::response(201, [], 'File gagal diupload.');
        }
    }

    public function getFileUploaded($idPeserta)
    {
        $files = DB::table('rikkes_fileupload')
            ->where('id_rikkes_peserta', $idPeserta)
            ->where('active', 1)
            ->get();
        return LibApp::response_success($files);
    }

    public function deleteImage(Request $request)
    {
        $update = DB::table('rikkes_fileupload')->where('id', $request->id)->update(array('active'=>0));
        return LibApp::response_success($update);
    }

}
