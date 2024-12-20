<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class CKEditorController extends Controller
{
    public function upload(Request $request,ImageUploadService $imageUploadService)
    {

        $prefix=$request->hasHeader('ids')?$request->header('ids'):date('d');

        if ($request->hasFile('upload'))
        {
            $image=$imageUploadService->uploadImage($request->file('upload'),$prefix);
            return response()->json([
                'uploaded' => true,
                'fileName' => $image['path'],
                'url' =>$image['full_url'],
            ]);
        }

    }

}
