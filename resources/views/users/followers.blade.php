@extends('users.show_parent')

@section('information')
    <div class="col-sm-12">
        フォロワーリスト
        @foreach ($user->followers as $follower)
            <div>
                <a href="{{ route('user.show', ['id' => $follower->id]) }}">{{ $follower->name }}</a>
            </div>
        @endforeach
    </div>
@endsection