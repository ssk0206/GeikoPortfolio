@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <h4>編集</h4>      
        <form action="/users/{{$user->id}}/edit" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
            </div>
            <div class="form-group">
                <label for="grade">学年</label>
                <select name="grade" class="form-control">
                    <option value="">選択してください</option>
                    <option value="B1" <?= $user->grade == 'B1' ? 'selected' : "" ?>>B1</option>
                    <option value="B2" <?= $user->grade == 'B2' ? 'selected' : "" ?>>B2</option>
                    <option value="B3" <?= $user->grade == 'B3' ? 'selected' : "" ?>>B3</option>
                    <option value="B4" <?= $user->grade == 'B4' ? 'selected' : "" ?>>B4</option>
                    <option value="M1" <?= $user->grade == 'M1' ? 'selected' : "" ?>>M1</option>
                    <option value="M2" <?= $user->grade == 'M2' ? 'selected' : "" ?>>M2</option>
                    <option value="その他" <?= $user->grade == 'その他' ? 'selected' : "" ?>>その他</option>
                </select>
            </div>
            <div class="form-group">
                <label for="grade">学科</label>
                <select name="department" class="form-control">
                    <option value="">選択してください</option>
                    <option value="環境設計学科" <?= $user->department == '環境設計学科' ? 'selected' : "" ?>>環境設計学科</option>
                    <option value="工業設計学科" <?= $user->department == '工業設計学科' ? 'selected' : "" ?>>工業設計学科</option>
                    <option value="画像設計学科" <?= $user->department == '画像設計学科' ? 'selected' : "" ?>>画像設計学科</option>
                    <option value="音響設計学科" <?= $user->department == '音響設計学科' ? 'selected' : "" ?>>音響設計学科</option>
                    <option value="芸術情報設計学科" <?= $user->department == '芸術情報設計学科' ? 'selected' : "" ?>>芸術情報設計学科</option>
                    <option value="その他" <?= $user->department == 'その他' ? 'selected' : "" ?>>その他</option>
                </select>
            </div>
            <div class="form-group">
                <label for="skill">特技・スキル</label>
                <textarea type="text" name="skill" class="form-control">{{ $user->skill }}</textarea>
            </div>
            <div class="form-group">
                <label for="self_introduction">自己紹介・やりたいこと</label>
                <textarea type="text" name="self_introduction" class="form-control">{{ $user->self_introduction }}</textarea>
            </div>
            <input type="submit" value="変更" class="btn btn-primary" id="upload">
        </form>
    </div>
    <div class="col-12 col-sm-12 col-md-12">
        <form action="{{ route('user.delete', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return deleteUser()">
            @csrf
            @method('DELETE')
            <input type="submit" value="退会" class="btn btn-outline-danger" id="delete">
        </form>
    </div>
@endsection