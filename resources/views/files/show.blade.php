@extends('layouts.app')

@section('content')
<div class="container">  
    <div style="display:inline-block; padding-bottom:20px;">
        <img src="{{$file->url}}" alt="" style="width:200px;">
        <p style="text-align:center;font-size:22px;">{{ mb_substr($file->file_name, 0,  mb_strlen($file->file_name) - 10) }}</p>
        <p style="text-align:center;">{{$file->user->name}}</p>
    </div>
    <div style="display:inline-block;">
        @if ($file->user_id === Auth::user()->id)
            <form action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST" style="display:inline">
                {{ csrf_field() }}
                <input type="hidden" name="_method" value="DELETE">
                <input type="submit" value="投稿削除">
            </form>
        @endif
    </div>
    <div style="padding-bottom:10px;">
        {{-- いいね機能 --}}
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
                <textarea name="content" id="" cols="40" rows="2">{{ old('content') }}</textarea>
                <input type="submit" value="コメント">
            </form>
        @endif
        <div>
            @foreach ($comments as $comment)
                <div>
                    <span style="font-weight:bold;padding-right:20px;">
                        {{$comment->user->name }}
                    </span>
                    {{ $comment->content }}
                    @if (! Auth::guest())
                        @if ($comment->user_id === Auth::user()->id)
                            <form action="{{ route('file.deleteComment', ['id' => $file->id, 'file_id' => $comment->id]) }}" method="POST" style="display:inline">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="submit" value="削除">
                            </form>
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
