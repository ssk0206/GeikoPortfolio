@extends('layouts.app')

@section('content')
<div class="container">
    <div style="margin-left:80px;">
        @foreach ($files as $file)
            <div style="display:inline-block;margin:5px;">
                <a href="/files/{{ $file->id }}">
                    <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail img-fluid"  style="width:220px; height:220px; object-fit: cover;" alt="">
                </a>
                <h6 style="text-align:center;font-weight:bold;">{{$file->file_name}}</h6>
                <div style="text-align:center;">{{$file->user->name}}</div>
            </div>
        @endforeach
        {{ $files->links() }}
    </div>
</div>
@endsection
