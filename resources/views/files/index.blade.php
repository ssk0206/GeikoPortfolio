@extends('layouts.app')

@section('content')
<div class="container">
    
    @foreach ($files as $file)
        <div style="display:inline-block">
            <a href="/files/{{ $file->id }}">
                <img src="{{$file->url}}" alt="" style="width:200px;">
            </a>
            <p style="text-align:center;">{{$file->user->name}}</p>
        </div>
    @endforeach
</div>
@endsection
