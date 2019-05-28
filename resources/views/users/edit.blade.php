@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <h4>編集</h4>      
        <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            {{-- ユーザー名 --}}
            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" name="name" class="form-control" value="{{$user->name}}" required>
            </div>
            {{-- 学年 --}}
            <div class="form-group">
                <label for="grade">学年</label>
                <select name="grade" class="form-control">
                    <option value="">選択してください</option>
                    @foreach ($grades as $g)
                        <option value="{{$g}}" <?= "{{$user->grade}}" === "{{$g}}" ? 'selected' : "" ?>>{{$g}}</option>
                    @endforeach              
                </select>
            </div>
            {{-- 学科 --}}
            <div class="form-group">
                <label for="grade">学科</label>
                <select name="department" class="form-control">
                    <option value="">選択してください</option>
                    @foreach ($departments as $d)
                        <option value="{{$d}}" <?= "{{$user->department}}" === "{{$d}}" ? 'selected' : "" ?>>{{$d}}</option>
                    @endforeach
                </select>
            </div>
            {{-- 特技 --}}
            <div class="form-group">
                <label for="skill">特技・スキル</label>
                <textarea type="text" name="skill" class="form-control">{{ $user->skill }}</textarea>
            </div>
            {{-- 自己紹介 --}}
            <div class="form-group">
                <label for="self_introduction">自己紹介・やりたいこと</label>
                <textarea type="text" name="self_introduction" class="form-control">{{ $user->self_introduction }}</textarea>
            </div>
            <input type="submit" value="変更" class="btn btn-primary" id="upload">
        </form>
    </div>
    {{-- 退会 --}}
    <div class="col-12 col-sm-12 col-md-12">
        <form action="{{ route('users.destroy', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data" onsubmit="return deleteUser()">
            @csrf
            @method('DELETE')
            <input type="submit" value="退会" class="btn btn-outline-danger" id="delete">
        </form>
    </div>
@endsection