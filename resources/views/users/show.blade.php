@extends('users.show_parent')

@section('information')
    <div class="col-md-9">
        <h4>作品集</h4>
        @foreach ($files as $file)
            <div style="display:inline-block;">
                <a href="/files/{{ $file->id }}">
                    <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail img-fluid"  style="width:160px; height:160px; object-fit: cover;" alt="">
                </a>
                <p style="text-align:center;">{{$file->file_name}}</p>
            </div>
        @endforeach
        {{ $files->links() }}
    </div> 
@endsection
