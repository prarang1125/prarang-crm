<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;

class ImageUploadService
{
    public function uploadImage($image,$prefix = 'upload', $folder = 'posts')
    {
        
        try {            
            $filename = $prefix . '_' . date('F_Y') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $disk = env('FILESYSTEM_DISK', 'local');             
            if ($disk === 's3') {                
                $data['path'] = Storage::disk('s3')->putFileAs($folder, $image, $filename);
                $data['url'] = Storage::disk('s3')->url($data['path']);
            } else {                
                $data['path'] = $image->storeAs($folder, $filename, 'public');
                $data['url'] = Storage::disk('public')->url($data['path']);
            }
            return [
                'storage_driver' => $disk,
                'path'           => $data['path'], 
                'full_url'       => $data['url'], 
            ];
        } catch (Exception $e) {
            return [
                'error'   => true,
                'message' => $e->getMessage(),
            ];
        }
    }
}
