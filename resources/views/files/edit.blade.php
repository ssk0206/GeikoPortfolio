@extends('layouts.app')

@section('content')
<div class="container">  
    <div class="col-md-12" style="padding-bottom:20px;">
        @if ($file->media_type == 'image')
            <img class="col-md-6" src="{{ route('file.get', [ 'id' => $file->id ]) }}" alt="" style="margin:10px 0; padding:0; max-height: 400px;object-fit:contain;">
        @else
            <video class="col-md-6" src="{{ route('file.get', [ 'id' => $file->id ]) }}" controls playsinline style="margin:10px 0; padding:0; max-height: 400px;" poster="{{ route('thumb', $file->id) }}"></video>
        @endif
        <form action="{{ route('file.edit', ['id' => $file->id]) }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
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
    <div class="col-md-3" style="margin-bottom:10px;">
        @if (Auth::check())
            @if ($file->user_id === Auth::user()->id)
                <form action="{{ route('file.delete', ['id' => $file->id]) }}" method="POST" style="display:inline" onsubmit="return submitChk()">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <input class="btn btn-outline-danger" type="submit" value="投稿削除">
                </form>
            @endif
        @endif
    </div>
</div>
@endsection

<script>
    /**
     * 確認ダイアログの返り値によりフォーム送信
    */
    function submitChk () {
        /* 確認ダイアログ表示 */
        var flag = confirm ( "投稿を削除してもよろしいですか？\n削除したくない場合は[キャンセル]ボタンを押して下さい");
        /* send_flg が TRUEなら送信、FALSEなら送信しない */
        return flag;
    }
</script>
