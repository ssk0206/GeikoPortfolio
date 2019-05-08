<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\File;



class FileController extends Controller
{

    public function __construct()
    {
        //認証が必要
        $this->middleware('auth')->except(['index']);
    }

    /**
     * ファイル一覧
     */
    public function index()
    {
        // $files = File::with(['user'])
        //         ->orderBy(File::CREATED_AT, 'desc')->paginate();
        $files = File::all();
        \Log::info(get_class($files));
        // $data = [];
        // foreach ($files as $m) {
        //     $data[]['name'] = $m->url;
        // }
        return view('files.index',['files' => $files]);
    }

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

        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );
        $length = count($characters);
        $id = "";
        for ($i = 0; $i < 10; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        $file = new File();
        $file->file_name = $request->file_name . $id;
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

        return redirect()->to('/');
    }
}
