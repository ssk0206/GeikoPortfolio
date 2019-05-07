@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/create" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label for="file_name">ファイル名</label>
        <input type="text" name="file_name" class="form-control">
        <label for="file">ファイルアップロード</label>
        <input type="file" name="file" class="form-control">
        <input type="submit" value="送信">
    </form>
</div>
@endsection
