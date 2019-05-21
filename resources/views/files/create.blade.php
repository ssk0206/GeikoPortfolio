@extends('layouts.app')

@section('content')
<div class="container">
    <div class="col-md-6">
      <h4 style="margin-bottom:50px;">投稿</h4>
      <form action="/create" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group">
            <input type="file" name="file" class="form-control-file" required>
          </div>
          <div class="form-group">
            <label for="file_name">ファイル名</label>
            <input type="text" name="file_name" class="form-control" required>
          </div>
          <input type="submit" value="投稿" class="btn btn-primary" id="upload">
      </form>
    </div>
</div>

@endsection
