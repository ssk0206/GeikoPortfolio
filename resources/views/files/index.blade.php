@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @foreach ($files as $file)
            <div class="col-6 col-sm-3 col-md-2">
                <a href="/files/{{ $file->id }}" class="center">
                    <img class="img-thumbnail thumb-img" src="{{ route('thumb', $file->id) }}">
                    <div class="thumb-name">{{$file->file_name}}</div>
                </a>  
                <div class="thumb-name">{{$file->user->name}}</div>
            </div>
        @endforeach
        <div class="links">{{ $files->links() }}</div>
    </div> 
</div>
@endsection
