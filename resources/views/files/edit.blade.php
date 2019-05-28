@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="col-md-12" style="padding-bottom:20px;">
        @if ($file->media_type == 'image')
            <img class="col-md-6 show-content" src="{{ route('file.get', [ 'id' => $file->id ]) }}">
        @else
            <video class="col-md-6 show-content" src="{{ route('file.get', [ 'id' => $file->id ]) }}" controls playsinline poster="{{ route('thumb', $file->id) }}"></video>
        @endif
        <form action="{{ route('file.edit', ['id' => $file->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
				<label for="file_name">ファイル名</label>
				<input type="text" name="file_name" class="form-control" value="{{ $file->file_name }}" required>
			</div>
			<div class="form-group">
				<label for="description">概要</label>
				<textarea class="form-control" name="description" id="" cols="60" rows="1" >{{ $file->description }}</textarea>
			</div>
			<input type="submit" value="編集" class="btn btn-primary" id="upload">
        </form>
       
    </div>
    {{-- 投稿削除ボタン --}}
    <div class="col-md-3 margin-b10">
        @if (Auth::check())
            @if ($file->user_id === Auth::user()->id)
                <form action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST" style="display:inline" onsubmit="return submitChk()">
                    @csrf
                    @method('DELETE')
                    <input class="btn btn-outline-danger" type="submit" value="投稿削除">
                </form>
            @endif
        @endif
    </div>
</div>
@endsection
