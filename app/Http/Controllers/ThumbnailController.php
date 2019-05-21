<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\File;
use Illuminate\Support\Facades\Storage;

class ThumbnailController extends Controller
{
    public function __invoke(int $id)
    {
        
        $file = File::where('id', $id)->first();

        if (! $file) {
            abort(404);
        }

        if ($file->media_type == 'image') {
            #$disk = Storage::disk('s3_2');
            try {
                $url = $file->s3_name;
                $url = file_get_contents('https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$url);
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
            }
            return response($url)->header('Content-Type', 'image/png');
        } elseif ($file->media_type == 'movie') {
            $disk = Storage::disk('s3_2');
            $url = '';
            $contents = $disk->files($file->folder);
            try {
                //$contents = $disk->files($file->folder);
                if (substr($contents['0'], -3) == 'png') {
                    $url = $contents['0'];
                } else {
                    $url = $contents['1'];
                }
                $url = file_get_contents('https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$url);
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
            }
            return response($url)->header('Content-Type', 'image/png');
        }
    }
}
