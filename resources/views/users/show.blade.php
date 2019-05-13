@extends('users.show_parent')

@section('information')
    <div class="col-sm-12">
            @foreach ($user->files as $file)
            <div style="display:inline-block;margin:10px;">
                <a href="/files/{{ $file->id }}">
                    <img src="{{$file->url}}" alt="" style="width:240px;">
                </a>
                <p style="text-align:center;">{{ mb_substr($file->file_name, 0,  mb_strlen($file->file_name) - 10) }}</p>
            </div>
        @endforeach
    </div> 
@endsection
