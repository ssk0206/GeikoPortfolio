@extends('users.show')

@section('information')
    フォロワーリスト
    @foreach ($user->followers as $follower)
        <div>
            <a href="{{ route('user.show', ['id' => $follower->id]) }}">{{ $follower->name }}</a>
        </div>
    @endforeach
@endsection