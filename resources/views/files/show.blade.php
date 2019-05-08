@extends('layouts.app')

@section('content')
<div class="container">  
    <div style="display:inline-block; padding-bottom:50px;">
        <img src="{{$file->url}}" alt="" style="width:200px;">
        <p style="text-align:center;">{{ mb_substr($file->file_name, 0,  mb_strlen($file->file_name) - 10) }}</p>
        <p style="text-align:center;">{{$file->user->name}}</p>
    </div>
    <div style="padding:0 0 0 80px;">
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
