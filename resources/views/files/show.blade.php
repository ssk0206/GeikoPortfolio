@extends('layouts.app')

@section('content')
<div class="container">  
    <div style="display:inline-block; padding-bottom:20px;">
        <img src="{{$file->url}}" alt="" style="width:200px;">
        <p style="text-align:center;">{{ mb_substr($file->file_name, 0,  mb_strlen($file->file_name) - 10) }}</p>
        <p style="text-align:center;">{{$file->user->name}}</p>
    </div>
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
        <form action="{{ route('file.comment', ['file' => $file]) }}" method="POST">
            {{ csrf_field() }}
            <textarea name="content" id="" cols="40" rows="2">{{ old('content') }}</textarea>
            <input type="submit" value="コメント">
        </form>
        <div>
            @foreach ($comments as $comment)
                <p><span style="font-weight:bold;padding-right:20px;">{{$comment->user->name }}</span>{{ $comment->content }}</p>
            @endforeach
        </div>
    </div>
</div>
@endsection
