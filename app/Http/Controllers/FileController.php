<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\File;



class FileController extends Controller
{
    /**
     * フォームを表示
     */
    public function showCreateForm()
    {
        return view('files.create');
    }

    /**
     * ファイル投稿
     * @param Request $request
     */
    public function create(Request $request)
    {
        // 画像かどうか
        $extension = $request->file->extension();
        $image_extension = ['jpg', 'jpeg', 'gif', 'png'];
        $key = in_array($extension, $image_extension);
        $media_type = '';
        if ($key) {
            $media_type = 'image';
        } else {
            return false;
        }

        $file = new File();
        $file->file_name = $request->file_name;
        $file->media_type = $media_type;

        // 第３引数のpublicはファイルを公開可能にするため
        Storage::cloud()->putFileAs('', $request->file, $file->file_name, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->files()->save($file);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            Storage::cloud()->delete($file->file_name);
            throw $exception;
        }
    }
}
