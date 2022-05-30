<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Lumen\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;
use App\Http\Libraries\LibApp;

class UploadController extends BaseController
{
    public function doUpload(Request $request)
    {
        $destination_path = base_path().'/public/uploads/';
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $image = Image::make($file->getRealPath());
        $image->save($destination_path.$name);

        $res = array(
            'image' => 'public/uploads/'.$name,
            'filename' => $name,
            'type' => $file->getMimeType(),
            'size' => round($file->getSize() / 1024 / 1024, 2).' Mb'
        );
        return LibApp::response_success($res);
    }

}
