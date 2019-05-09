<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\Http\Requests\StoreComment;
use App\Comment;
use App\Http\Requests\StoreFileRequest;

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
        $files = File::with(['user'])
                ->orderBy(File::CREATED_AT, 'desc')->paginate();
        
        return view('files.index',['files' => $files]);
    }

    public function show(int $id)
    {
        $file = File::where('id', $id)->with(['user', 'comments.user'])->first();

        return view('files.show', ['file' => $file, 'comments' => $file->comments ]);
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
     * @param StoreFileRequest $request
     */
    public function create(StoreFileRequest $request)
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
        // \Log::info($request->file_name);
        $file->file_name = $request->file_name . $id;
        // \Log::info($request->file_name . $id );
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

    /**
     * コメント投稿
     * @param File $file
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(File $file, StoreComment $request) 
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->file_id = $file->id;
        $comment->user_id = Auth::user()->id;
        $file->comments()->save($comment);

        return redirect()->route('file.show', ['id' => $file->id]);
    }
}
