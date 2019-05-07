@extends('layouts.app')

@section('content')
<div class="container">
    
    @foreach ($files as $file)
        <div style="display:inline-block">
            <img src="{{$file->url}}" alt="" style="width:200px;">
            <p style="text-align:center;">{{$file->user->name}}</p>
        </div>
    @endforeach
</div>
@endsection
