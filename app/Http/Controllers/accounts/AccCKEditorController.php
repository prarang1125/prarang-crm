<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AccCKEditorController extends Controller
{
  
    public function accUpload(Request $request,ImageUploadService $imageUploadService)
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
