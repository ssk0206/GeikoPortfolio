@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($files as $file)
        <div style="display:inline-block;margin:10px;">
            <a href="/files/{{ $file->id }}">
                @if ($file->media_type === 'image')
                    <img src="{{$file->url}}" alt="" style="width:200px;">
                @else
                    <video src="{{$file->url}}" style="width:200px;" id="video">
                        <source src="myVideo.mp4" type="video/mp4" />
                        <source src="myVideo.webm" type="video/webm" />
                    </video>
                @endif
            </a>
            <p style="text-align:center;">{{$file->user->name}}</p>
        </div>
    @endforeach

    {{ $files->links() }}
</div>
@endsection
