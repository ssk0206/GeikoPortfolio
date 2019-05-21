@extends('users.show_parent')

@section('information')
    <div class="col-sm-12">
        @foreach ($files as $file)
            <div style="display:inline-block;margin:10px;">
                <a href="/files/{{ $file->id }}">
                    @if ($file->media_type === 'image')
                        <img src="{{$file->image_url}}" style="width:300px;" alt="">
                    @else
                        <img src="{{ route('thumb', $file->id) }}" style="width:300px;" alt="">
                    @endif
                </a>
                <p style="text-align:center;">{{$file->file_name}}</p>
            </div>
        @endforeach
        {{ $files->links() }}
    </div> 
@endsection
