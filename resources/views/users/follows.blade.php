@extends('users.show')

@section('information')
    フォローリスト
    @foreach ($user->follows as $follow)
        <div>
            <a href="{{ route('user.show', ['id' => $follow->id]) }}">{{ $follow->name }}</a>
        </div>
    @endforeach
@endsection