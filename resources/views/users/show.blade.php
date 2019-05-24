@extends('users.show_parent')

@section('information') 
    <div class="col-md-9 row justify-content-center" style="margin-left:1px;">
        <h4 class="col-12 col-sm-12 col-md-12" style="padding:0;">作品集</h4>
        @foreach ($files as $file)
            <div class="col-6 col-sm-4 col-md-3"  style="padding:5px;text-align:center;">
                <a href="/files/{{ $file->id }}" style="text-alian:center;">
                    <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail"  style="width:150px; height:150px; object-fit: cover;" alt="">
                </a>
                <div style="text-align:center;max-width:150px;margin:auto;">{{$file->file_name}}</div>
            </div>
        @endforeach
        <div style="margin: 20px 0 0;">{{ $files->links() }}</div>
    </div> 
@endsection
