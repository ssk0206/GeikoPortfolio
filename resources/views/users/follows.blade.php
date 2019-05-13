@extends('users.show_parent')

@section('information')
    <div class="col-sm-12">
        フォローリスト
        @foreach ($user->follows as $follow)
            <div>
                <a href="{{ route('user.show', ['id' => $follow->id]) }}">{{ $follow->name }}</a>
            </div>
        @endforeach
    </div>
@endsection