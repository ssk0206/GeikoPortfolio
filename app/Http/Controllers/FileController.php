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
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * ファイル一覧
     */
    public function index()
    {
        $files = File::with(['user'])
                ->orderBy(File::CREATED_AT, 'desc')->paginate(12);
        
        return view('files.index',['files' => $files]);
    }

    /**
     * ファイル詳細画面
     */
    public function show(int $id)
    {
        $file = File::where('id', $id)->with(['user', 'comments.user'])->first();

        if (! $file) {
            abort(404);
        }

        return view('files.show', ['file' => $file, 'comments' => $file->comments, 'extension' => $file->extension ]);
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
        $extension = $request->file->extension();

        $image_extension = ['jpg', 'jpeg', 'gif', 'png'];
        $movie_extension = ['mp4', 'mov'];

        $key_image = in_array($extension, $image_extension);
        $key_movie = in_array($extension, $movie_extension);

        $media_type = '';
        if ($key_image) {
            $media_type = 'image';
        } elseif ($key_movie) {
            $media_type = 'movie';
        } else {
            return false;
        }

        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );
        $length = count($characters);
        $s3_id = "";
        for ($i = 0; $i < 12; $i++) {
            $s3_id .= $characters[random_int(0, $length - 1)];
        }

        $file = new File();
        $file->file_name = $request->file_name;
        $file->s3_name = $s3_id.'.' .$extension;
        $file->extension = $extension;
        $file->media_type = $media_type;

        // 第３引数のpublicはファイルを公開可能にするため
        Storage::cloud()->putFileAs('', $request->file, $file->s3_name, 'public');

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->files()->save($file);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            Storage::cloud()->delete($file->s3_name);
            throw $exception;
        }

        return redirect()->to('/');
    }

    /**
     * ファイル削除
     * @param int $id
     */
    public function delete(int $id)
    {
        $file = File::where('id', $id)->first();

        if ($file->user->id === Auth::user()->id) {
            $file->delete();
        }

        return redirect()->to('/');
    }

    /**
     * コメント投稿
     * @param File $file
     * @param StoreComment $request
     * @return 
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

    /**
     * コメント削除
     * @param int $id
     * @param int $comment_id
     * @return 
     */
    public function deleteComment(int $id, int $comment_id)
    {
        $comment = Comment::where('id', $comment_id)->first();
        $user_id = $comment->user_id;

        if ($user_id === Auth::user()->id) {
            $comment->delete();
        }

        return redirect()->route('file.show', ['id' => $id]);
    }

    /**
     * いいね
     * @param int $id
     */
    public function like(int $id)
    {
        $file = File::where('id', $id)->with('likes')->first();

        if (! $file) {
            abort(404);
        }

        $file->likes()->detach(Auth::user()->id);
        $file->likes()->attach(Auth::user()->id);

        return redirect()->route('file.show', ['id' => $file->id]);
    }

    /**
     * いいね解除
     * @param int $id
     */
    public function unlike(int $id)
    {
        $file = File::where('id', $id)->with('likes')->first();

        if (! $file) {
            abort(404);
        }

        $file->likes()->detach(Auth::user()->id);

        return redirect()->route('file.show', ['id' => $file->id]);
    }
}
