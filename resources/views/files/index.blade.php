@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" style="margin-left:0px;">
        @foreach ($files as $file)
            <div class="col-xs-2" style="display:inline-block;margin:0px;padding:0;">
                <a href="/files/{{ $file->id }}">
                    <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail img-fluid"  style="width:142px; height:142px; object-fit: cover;" alt="">
                </a>
                <h6 style="text-align:center;font-weight:bold;">{{$file->file_name}}</h6>
                <div style="text-align:center;">{{$file->user->name}}</div>
            </div>
        @endforeach       
    </div>
    {{ $files->links() }}
</div>
@endsection
