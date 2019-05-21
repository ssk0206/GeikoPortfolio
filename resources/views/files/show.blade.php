@extends('layouts.app')

@section('content')
<div class="container">  
    <div style="display:inline-block; padding-bottom:20px;">
        @if ($file->media_type == 'image')
            <img src="{{ route('file.get', [ 'id' => $file->id ]) }}" alt="" style="width:300px;margin:10px 0;">
        @else
            <video src="{{ route('file.get', [ 'id' => $file->id ]) }}" style="width:400px;margin:10px 0;" controls></video>
        @endif
        
        <p style="text-align:center;font-size:22px;">{{ $file->file_name }}</p>
        <p style="text-align:center;font-size:20px;">{{$file->user->name}}</p>
    </div>
    {{-- 投稿削除ボタン --}}
    <div style="display:inline-block;">
        @if (Auth::check())
            @if ($file->user_id === Auth::user()->id)
                <form action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST" style="display:inline">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input class="btn btn-danger" type="submit" value="投稿削除">
                </form>
            @endif
        @endif
    </div>
    {{-- いいね機能 --}}
    <div style="padding-bottom:10px;">      
        @if ($file->liked_by_user)
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit"  style="background:hotpink;color:white;">
                    <i class="icon ion-md-heart" style="padding-right:6px;"></i>{{$file->likes_count}}
                </button>
            </form>
        @else
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="PUT">
                <button type="submit" >
                    <i class="icon ion-md-heart" style="color:hotpink; padding-right:6px;"></i>{{$file->likes_count}}
                </button>
            </form>
        @endif
    </div>
    <div>
        @if (Auth::check())
            <form action="{{ route('file.comment', ['file' => $file]) }}" method="POST">
                {{ csrf_field() }}
                <textarea name="content" id="" cols="54" rows="1" style="vertical-align : middle;">{{ old('content') }}</textarea>
                <input class="btn btn-primary" type="submit" value="コメント">
            </form>
        @endif
        <div>
            @foreach ($comments as $comment)
                <div style="margin:10px;">
                    <span style="font-weight:bold;padding-right:20px;">
                        {{$comment->user->name }}
                    </span>
                    {{ $comment->content }}
                    @if (! Auth::guest())
                        @if ($comment->user_id === Auth::user()->id)
                            <form action="{{ route('file.deleteComment', ['id' => $file->id, 'file_id' => $comment->id]) }}" method="POST" style="display:inline">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <input class="btn btn-danger" type="submit" value="削除">
                            </form>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
