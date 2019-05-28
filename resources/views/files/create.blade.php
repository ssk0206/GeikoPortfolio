@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-lg-8 offset-lg-2 col-md-12">
			<h4 class="margin-b20">投稿</h4>
			<form action="/create" method="POST" enctype="multipart/form-data">
				@csrf
				<div class="form-group">
					<label for="file">動画・画像（100MB以下のファイル）</label>
					<input type="file" name="file" class="form-control-file" required>
				</div>
				<div class="form-group">
					<label for="file_name">ファイル名</label>
					<input type="text" name="file_name" class="form-control" required>
				</div>
				<div class="form-group">
					<label for="description">概要</label>
					<textarea class="form-control" name="description" id="" cols="60" rows="1" >{{ old('content') }}</textarea>
				</div>
				<input type="submit" value="投稿" class="btn btn-primary" id="upload">
			</form>
		</div>
	</div>
</div>
@endsection
