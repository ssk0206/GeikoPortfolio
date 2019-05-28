@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="col-md-12" style="padding-bottom:20px;">
        @if ($file->media_type == 'image')
            <img class="col-md-6 show-content" src="{{ route('file.get', [ 'id' => $file->id ]) }}" alt="">
        @else
            <video class="col-md-6 show-content" src="{{ route('file.get', [ 'id' => $file->id ]) }}" controls playsinline poster="{{ route('thumb', $file->id) }}"></video>
        @endif
        <h3 style="font-size:20px;">{{ $file->file_name }}</h3>
        <h4 style="font-size:18px;">{{$file->user->name}}</h4>
        <hr>
        <div>
            {{ $file->description }}
        </div>
    </div>
    <div class="col-md-3 margin-b10">
        @if (Auth::check())
            @if ($file->user_id === Auth::user()->id)
                <a class="btn btn-outline-primary" href="{{ route('file.edit', ['id' => $file->id ]) }}" value="投稿削除">投稿編集</a>
            @endif
        @endif
    </div>
    
    {{-- いいね機能 --}}
    <div class="col-md-3 margin-b10">      
        @if ($file->liked_by_user)
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-nomal unlike-button">
                    <i class="icon ion-md-heart unlike-i"></i>{{$file->likes_count}}
                </button>
            </form>
        @else
            <form action="{{ route('file.like', ['id' => $file->id]) }}" method="POST">
                @csrf
                @method('PUT')
                <button type="submit"  class="btn btn-momal like-button">
                    <i class="icon ion-md-heart like-i"></i>{{$file->likes_count}}
                </button>
            </form>
        @endif
    </div>
    <hr>
    <div class="col-md-8">
        @if (Auth::check())
            <form action="{{ route('file.comment', ['file' => $file]) }}" method="POST">
                @csrf
                <div class="form-group inline-flex">
                    <textarea class="form-control" name="content" id="" cols="60" rows="1" >{{ old('content') }}</textarea>
                    <input class="btn btn-primary" type="submit" value="コメント">
                </div>
            </form>
        @endif
        <div>
            @foreach ($comments as $comment)
                <div class="comments">
                    <span class="comment">
                        {{$comment->user->name }}
                    </span>
                    <span class="comment">
                        {{ $comment->content }}
                    </span>
                    @if (! Auth::guest())
                        @if ($comment->user_id === Auth::user()->id)
                            <form action="{{ route('file.deleteComment', ['id' => $file->id, 'file_id' => $comment->id]) }}" method="POST" style="display:inline"  onsubmit="return submitChk()">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
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