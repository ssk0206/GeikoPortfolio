@extends('users.show_parent')

@section('information')
    <div class="col-md-9">
        <h4>作品集</h4>
        @foreach ($files as $file)
            <div style="display:inline-block;" class="col-md-3">
                <a href="/files/{{ $file->id }}">
                    @if ($file->media_type === 'image')
                        <img src="{{$file->image_url}}" class="img-thumbnail img-fluid" alt="">
                    @else
                        <img src="{{ route('thumb', $file->id) }}" class="img-thumbnail img-fluid" alt="">
                    @endif
                </a>
                <p style="text-align:center;">{{$file->file_name}}</p>
            </div>
        @endforeach
        {{ $files->links() }}
    </div> 
@endsection
