@extends('layouts.app')

@section('content')
<div class="container">
    <form action="/create" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label for="file_name">ファイル名</label>
        <input type="text" name="file_name" class="form-control">
        <label for="file">ファイルアップロード</label>
        <input type="file" name="file" class="form-control">
        <input type="submit" value="送信" class="btn btn-primary" id="upload">
    </form>
    <canvas id="canvas" width="0" height="0"></canvas>
</div>


<!-- 以下、javascript -->
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script type="text/javascript">
    $(function() {
      var file = null; // 選択されるファイル
      var blob = null; // 画像(BLOBデータ)
      const THUMBNAIL_WIDTH = 500; // 画像リサイズ後の横の長さの最大値
      const THUMBNAIL_HEIGHT = 500; // 画像リサイズ後の縦の長さの最大値
      
      // ファイルが選択されたら
      $('input[type=file]').change(function() {
        
        // ファイルを取得
        file = $(this).prop('files')[0];
        // 選択されたファイルが画像かどうか判定
        if (!file){
            var canvas = $('#canvas');                      
            var ctx = canvas[0].getContext('2d');
            // canvasに既に描画されている画像をクリア
            ctx.clearRect(0,0,500,500);
            return;
        }else if (file.type != 'image/jpeg' && file.type != 'image/png') {
            // 画像でない場合は終了
            file = null;
            blob = null;
            return;
        }
    
        // 画像をリサイズする
        var image = new Image();
        var reader = new FileReader();
        reader.onload = function(e) {
            image.onload = function() {
            var width, height;
            if(image.width > image.height){
              // 横長の画像は横のサイズを指定値にあわせる
              var ratio = image.height/image.width;
              width = THUMBNAIL_WIDTH;
              height = THUMBNAIL_WIDTH * ratio;
            } else {
              // 縦長の画像は縦のサイズを指定値にあわせる
              var ratio = image.width/image.height;
              width = THUMBNAIL_HEIGHT * ratio;
              height = THUMBNAIL_HEIGHT;
            }
            // サムネ描画用canvasのサイズを上で算出した値に変更
            var canvas = $('#canvas')
                         .attr('width', width)
                         .attr('height', height);
            var ctx = canvas[0].getContext('2d');
            // canvasに既に描画されている画像をクリア
            ctx.clearRect(0,0,width,height);
            // canvasにサムネイルを描画
            ctx.drawImage(image,0,0,image.width,image.height,0,0,width,height);
            }
          image.src = e.target.result;
        }
        reader.readAsDataURL(file);
    });

});
</script>

@endsection
