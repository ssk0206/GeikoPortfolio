@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h2><a href="{{ route('users.show', ['id' => $user->id]) }}">{{$user->name}}</a></h2>
            <div class="margin-b20">
                <div class="margin-b10">
                    {{$user->follows_count}}
                    <a href="{{ route('users.follows', ['id' => $user->id]) }}">フォロー</a>
        
                    {{$user->followers_count}}
                    <a href="{{ route('users.followers', ['id' => $user->id]) }}">フォロワー</a>
                </div>
                <div class="margin-b10"><span class="gray">学年：</span>{{$user->grade}}</div>
                <div class="margin-b10"><span class="gray">学科：</span>{{$user->department}}</div>
                <div class="margin-b10"><span class="gray">特技・スキル：</span><br>{{$user->skill}}</div>
                <div><span class="gray">自己紹介・やりたいこと：</span><br>{{$user->self_introduction}}</div>
                <hr>
                <div>
                    @auth
                        @if ($user->id !== Auth::user()->id)
                            @if ($user->followed_by_user)
                                <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-info">
                                        フォロー中
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-outline-info" >
                                        フォロー
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-info">
                                ユーザー情報編集
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @yield('information')
    </div>
</div>
@endsection