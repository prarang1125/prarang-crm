<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class AccCKEditorController extends Controller
{
  
    // public function accUpload(Request $request,ImageUploadService $imageUploadService)
    // {        
    //     $prefix=$request->hasHeader('ids')?$request->header('ids'):date('d');

    //     if ($request->hasFile('upload'))
    //     {
    //         $image=$imageUploadService->uploadImage($request->file('upload'),$prefix);
    //         return response()->json([
    //             'uploaded' => true,
    //             'fileName' => $image['path'],
    //             'url' =>$image['full_url'],
    //         ]);
    //     }
    // }

    public function upload(Request $request, ImageUploadService $imageUploadService)
    {
        $prefix = $request->hasHeader('ids') ? $request->header('ids') : date('d');

        if ($request->hasFile('upload')) {
            $file = $request->file('upload');

            // Validate image or video file types
            $allowedExtensions = ['jpeg', 'jpg', 'png', 'gif', 'mp4', 'avi', 'mov', 'webm'];
            $extension = $file->getClientOriginalExtension();

            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(['error' => 'Invalid file type.'], 400);
            }

            $media = $imageUploadService->uploadImage($file, $prefix);

            return response()->json([
                'uploaded' => true,
                'fileName' => $media['path'],
                'url' => $media['full_url'],
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }
}
