<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\File;
use App\Http\Requests\StoreComment;
use Illuminate\Http\Response;
use App\Comment;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\EditFileRequest;
use FFMpeg;
use FFMpeg\FFProbe;
use FFMpeg\Driver\FFMpegDriver;

//use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;

class FileController extends Controller
{
    
    public function __construct()
    {
        //ログインが必要ではないもの
        $this->middleware('auth')->except(['index', 'show', 'getFile']);
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
     * ファイルを取得
     */
    public function getFile(int $id, Request $request, Response $response)
    {
        $file = File::where('id', $id)->first();       

        if (! $file) {
            abort(404);
        }

        $disk = Storage::disk('s3_transcoder');
        
        if ($file->media_type == 'image') {
            $url = '';
            try {
                $url = file_get_contents($disk->url($file->s3_name));
            } catch (\Exception $e) {
                $file = file_get_contents(storage_path('app/public/noimage.jpg'));
                return response($file)->header('Content-Type', 'image/jpeg');
            }      
            return response($url)->header('Content-Type', 'image/png');

        } elseif ($file->media_type == 'movie') {
            try {
                // private関数 getMovie
                return self::getMovie($file, $request, $response);
            } catch (\Exception $e) {
                $file = file_get_contents(storage_path('app/public/noimage.jpg'));
                return response($file)->header('Content-Type', 'image/jpeg');
            }
        }
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

        $image_extension = ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'svg'];
        $movie_extension = ['mp4', 'mov', 'qt'];

        $media_type = '';
        $disk = '';
        // ファイルが画像か動画か
        if (in_array($extension, $image_extension)) {
            $media_type = 'image';
            $disk = Storage::disk('s3_transcoder');
        } elseif (in_array($extension, $movie_extension)) {
            $media_type = 'movie';
            $disk = Storage::disk('s3_original');
        } else {
            return redirect()->to('/create');
        }

        //s3用のID生成
        $s3_id = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-'), 0, 12);
        var_dump($request->description);
        $file = new File();
        $file->file_name = $request->file_name;
        $file->description = $request->description;
        $file->s3_name = $s3_id.'.' .$extension;
        $file->extension = $extension;
        $file->media_type = $media_type;

        $disk->putFileAs('', $request->file, $file->s3_name, 'public'); 
  
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

        return redirect()->to('/')->with('message', 'ファイルを投稿しました。');
    }

    /**
     * ファイル編集画面
     * @param int $id
     */
    public function showEditForm(int $id)
    {
        $file = File::where('id', $id)->first();

        if (! $file) {
            abort(404);
        }

        return view('files.edit', ['file' => $file]);
    }

    /**
     * ファイル編集
     * @param EditFileRequest $request
     * @param int $id
     */
    public function edit(EditFileRequest $request, int $id)
    {
        $file = File::where('id', $id)->first();

        if (! $file) {
            abort(404);
        }

        $file->file_name = $request->file_name;
        $file->description = $request->description;
        $file->save();

        return redirect()->route('file.show', ['file' => $file, 'comments' => $file->comments, 'extension' => $file->extension ])
                        ->with('message', '編集が完了しました。');
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

        return redirect()->to('/')->with('message', 'ファイルを削除しました。');
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


    /**
     * 動画を取得
     */
    private static function getMovie($file, Request $request, Response $response)
    {
        $disk = Storage::disk('s3_transcoder');
        $contents = $disk->files($file->folder);
        $url = '';
        if (substr($contents['0'], -3) == 'mp4') {
            $url = $contents['0'];
        } else {
            $url = $contents['1'];
        }
        $lines = file_get_contents($disk->url($url));
        $size = strlen($lines);
        $start = 0;
        $etag = md5($request->path()).$size;

        // ブラウザがHTTP_RANGEを要求してきた場合
        if($request->server('HTTP_RANGE', false)){
            // 要求された開始位置と終了位置を取得
            list($start,$end) = sscanf($request->server('HTTP_RANGE'),"bytes=%d-%d");
            // 終了位置が指定されていない場合(適当に1000000bytesづつ出す)
            if(empty($end)) {
                $end = $start + 1000000 - 1;
            }
            // 終了位置がファイルサイズを超えた場合
            if($end>=($size-1)) {
                $end = $size - 1;
            }
            // 部分コンテンツであることを伝え、コンテンツ範囲を伝える
            $response->setStatusCode(206)
                    ->header("Content-Range", "bytes {$start}-{$end}/{$size}");
            // 実際に送信するコンテンツ長: 終了位置 - 開始位置 + 1
            $size = $end - $start + 1;
        }

        // HTTP_RANGE(部分リクエスト)に対応していることを伝える
        $response->setContent(substr($lines, $start, $size))
                ->header("Accept-Ranges", "bytes")
                ->header("Content-Type", "video/mp4")
                ->header("Content-Length", "{$size}")
                ->header("Etag", "\"{$etag}\"");              
        return $response;
    }
}
