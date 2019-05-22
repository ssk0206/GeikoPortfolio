@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="col-md-12" style="padding-bottom:20px;">
        @if ($file->media_type == 'image')
            <img class="col-md-6" src="{{ route('file.get', [ 'id' => $file->id ]) }}" alt="" style="margin:10px 0; padding:0;">
        @else
            <video class="col-md-6" src="{{ route('file.get', [ 'id' => $file->id ]) }}" controls playsinline style="margin:10px 0; padding:0;"></video>
        @endif
        
        <h3 style="font-size:20px;">{{ $file->file_name }}</h3>
        <h4 style="font-size:18px;">{{$file->user->name}}</h4>
    </div>
    {{-- 投稿削除ボタン --}}
    <div class="col-md-3" style="margin-bottom:10px;">
        @if (Auth::check())
            @if ($file->user_id === Auth::user()->id)
                <form action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST" style="display:inline" onsubmit="return submitChk()">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input class="btn btn-outline-danger" type="submit" value="投稿削除">
                </form>
            @endif
        @endif
    </div>
    
    {{-- いいね機能 --}}
    <div class="col-md-3" style="padding-bottom:10px;">      
        @if ($file->liked_by_user)
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-nomal" style="background:hotpink;color:white;">
                    <i class="icon ion-md-heart" style="padding-right:6px;"></i>{{$file->likes_count}}
                </button>
            </form>
        @else
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <button type="submit"  class="btn btn-momal" style="border:hotpink 1px solid;" >
                    <i class="icon ion-md-heart" style="color:hotpink; padding-right:6px;"></i>{{$file->likes_count}}
                </button>
            </form>
        @endif
    </div>
    <hr>
    <div class="col-md-8">
        @if (Auth::check())
            <form action="{{ route('file.comment', ['file' => $file]) }}" method="POST">
                {{ csrf_field() }}
                <div class="form-group" style="display:inline-flex">
                    <textarea class="form-control" name="content" id="" cols="60" rows="1" >{{ old('content') }}</textarea>
                    <input class="btn btn-primary" type="submit" value="コメント">
                </div>
            </form>
        @endif
        <div>
            @foreach ($comments as $comment)
                <div style="margin:10px;">
                    <span style="font-weight:bold;padding-right:20px;">
                        {{$comment->user->name }}
                    </span>
                    <span style="font-weight:bold;padding-right:20px;">
                        {{ $comment->content }}
                    </span>
                    @if (! Auth::guest())
                        @if ($comment->user_id === Auth::user()->id)
                            <form action="{{ route('file.deleteComment', ['id' => $file->id, 'file_id' => $comment->id]) }}" method="POST" style="display:inline"  onsubmit="return submitChk()">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <input class="btn btn-outline-danger" type="submit" value="削除">
                            </form>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

<script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "投稿を削除してもよろしいですか？\n削除したくない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>
