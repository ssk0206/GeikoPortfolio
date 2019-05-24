@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @foreach ($files as $file)
            <div class="col-6 col-sm-3 col-md-2" style="padding:5px;text-align:center;">
                <a href="/files/{{ $file->id }}" style="text-alian:center;">
                    <img class="img-thumbnail" src="{{ route('thumb', $file->id) }}" style="object-fit: cover; height:150px;width:150px;" alt="">
                    <div style="text-align:center;font-weight:bold;max-width:150px;margin:auto;">{{$file->file_name}}</div>
                </a>  
                <div style="text-align:center;max-width:150px;margin:auto;">{{$file->user->name}}</div>
            </div>
        @endforeach
        <div style="margin: 20px 0 0;">{{ $files->links() }}</div>
    </div> 
</div>
@endsection
