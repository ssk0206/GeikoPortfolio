<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\Storage;

class ThumbnailController extends Controller
{
    public function __invoke(int $id)
    {
        $file = File::findOrFail($id);
        $disk = Storage::disk('s3_transcoder');
        
        if ($file->media_type == 'image') {
            
            try {
                $url = $file->s3_name;
                $url = file_get_contents($disk->url($file->s3_name));
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
            }
            return response($url)->header('Content-Type', 'image/png');
        } elseif ($file->media_type == 'movie') {
            
            $url = '';
            $contents = $disk->files($file->folder);
            try {
                if (substr($contents['0'], -3) == 'png') {
                    $url = $contents['0'];
                } else {
                    $url = $contents['1'];
                }
                $url = file_get_contents($disk->url($url));
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
            }
            return response($url)->header('Content-Type', 'image/png');
        }
    }
}
