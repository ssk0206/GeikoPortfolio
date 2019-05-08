@extends('layouts.app')

@section('content')
<div class="container">  
    <div style="display:inline-block">
        <img src="{{$file->url}}" alt="" style="width:200px;">
        <p style="text-align:center;">{{$file->user->name}}</p>
    </div>
</div>
@endsection
