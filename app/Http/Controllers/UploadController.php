<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Laravel\Lumen\Routing\Controller as BaseController;
use Intervention\Image\ImageManagerStatic as Image;

class UploadController extends BaseController
{
    public function doUpload(Request $request)
    {
        $destination_path = '../uploads/';
        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $image_resize = Image::make($file->getRealPath());
        $image_resize->resize(300, 300);
        $image_resize->save($destination_path.$name);

        $json = json_encode(array('imageUrl'=>'/uploads/'.$name));
        return $json;
    }
}
