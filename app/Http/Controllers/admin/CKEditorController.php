<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {

        // print_r($request->upload);
        // die();
        if ($request->hasFile('upload'))
        {
           $originName = $request->file('upload')->getClientOriginalName();
           $fileName   = pathinfo($originName, PATHINFO_FILENAME);
           $extension  = $request->file('upload')->getClientOriginalExtension();
           $fileName   = $fileName . '_' . time() . '.' .$extension;
           $request->file('upload')->move(public_path('uploads/ckeditor_images'), $fileName);
           $url = asset('uploads/ckeditor_images/' . $fileName);

           return response()->json([
                'uploaded' => true,
                'fileName' => $fileName,
                'url' => $url,
            ]);
        }

    }

    /**public function upload(Request $request)
    {
        if ($request->hasFile('upload'))
        {
            $file = $request->file('upload');

            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/ckeditor_images'), $fileName);

            $url = asset('uploads/ckeditor_images/' . $fileName);
            dd($url);

            return response()->json([
                'uploaded' => true,
                'fileName' => $fileName,
                'url' => $url,
            ]);
        }

        return response()->json([
            'uploaded' => false,
            'error' => [
                'message' => 'File upload failed. Please try again.',
            ],
        ]);
    }*/
}
