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
    public function getFile(int $id)
    {
        $file = File::where('id', $id)->first();

        if (! $file) {
            abort(404);
        }

        if ($file->media_type == 'image') {
            try {
                $url = $file->s3_name;
                $url = file_get_contents('https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$url);
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
            }
            return response($url)->header('Content-Type', 'image/png');

        } elseif ($file->media_type == 'movie') {
            try {
                $disk = Storage::disk('s3_2');
                $url = '';
                $contents = $disk->files($file->folder);

                if (substr($contents['0'], -3) == 'mp4') {
                    $url = $contents['0'];
                } else {
                    $url = $contents['1'];
                }

                $lines = file_get_contents('https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$url);
                $start = 0;
                $file = 'https://s3-ap-northeast-1.amazonaws.com/transcoder-data/'.$url;
                $headers = get_headers($file, 1);

                if ((!array_key_exists("Content-Length", $headers))) { return redirect()->to('/'); }

                $size = $headers["Content-Length"];

                // コンテンツの識別子
                $etag = md5($_SERVER["REQUEST_URI"]).$size;

                // ブラウザがHTTP_RANGEを要求してきた場合
                if(@$_SERVER["HTTP_RANGE"]){

                    // 要求された開始位置と終了位置を取得
                    list($start,$end) = sscanf($_SERVER["HTTP_RANGE"],"bytes=%d-%d");

                    // 終了位置が指定されていない場合(適当に1000000bytesづつ出す)
                    if(empty($end)) {
                        $end = $start + 10000 - 1;
                    }
                    // 終了位置がファイルサイズを超えた場合
                    if($end>=($size-1)) {
                        $end = $size - 1;
                    }

                    // 部分コンテンツであることを伝える
                    header("HTTP/1.1 206 Partial Content");

                    // コンテンツ範囲を伝える
                    header("Content-Range: bytes {$start}-{$end}/{$size}");

                    // 実際に送信するコンテンツ長: 終了位置 - 開始位置 + 1
                    $size = $end - $start + 1;
                }

                // HTTP_RANGE(部分リクエスト)に対応していることを伝える
                header("Accept-Ranges: bytes");
                header("Content-Type: video/mp4");
                header("Content-Length: {$size}");
                header("Etag: \"{$etag}\"");

                if($size) {
                    echo substr($lines, $start, $size);
                }

                exit;
            } catch (\Exception $e) {
                header('Content-Type: image/jpeg');
                return readfile('noimage.jpg');
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

        $key_image = in_array($extension, $image_extension);
        $key_movie = in_array($extension, $movie_extension);

        $media_type = '';
        if ($key_image) {
            $media_type = 'image';
        } elseif ($key_movie) {
            $media_type = 'movie';
        } else {
            return redirect()->to('/create');
        }

        #s3用のID生成
        $s3_id = substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-'), 0, 12);

        $file = new File();
        $file->file_name = $request->file_name;
        $file->s3_name = $s3_id.'.' .$extension;
        $file->extension = $extension;
        $file->media_type = $media_type;

        // 第３引数のpublicはファイルを公開可能にするため
        if ($file->media_type == 'movie') {
            $disk = Storage::disk('s3');
            $disk->putFileAs('', $request->file, $file->s3_name, 'public');   
        } elseif ($file->media_type == 'image') {
            $disk = Storage::disk('s3_2');
            $disk->putFileAs('', $request->file, $file->s3_name, 'public');
        }
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
}
