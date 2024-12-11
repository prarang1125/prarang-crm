<?php

namespace App\Http\Controllers\accounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccCKEditorController extends Controller
{
    public function accUpload(Request $request)
    {
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
}
