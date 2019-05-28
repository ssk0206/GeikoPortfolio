@extends('users.show_parent')

@section('information') 
    <div class="col-md-9 row justify-content-center" style="margin-left:1px;">
        <h4 class="col-12 col-sm-12 col-md-12">作品集</h4>
        @foreach ($files as $file)
            <div class="col-6 col-sm-4 col-md-3" class="files center">
                <a href="/files/{{ $file->id }}" class="center">
                    <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail thumb-img">
                    <div class="thumb-name">{{$file->file_name}}</div>
                </a>     
            </div>
        @endforeach
        <div class="links">{{ $files->links() }}</div>
    </div> 
@endsection
