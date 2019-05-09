@extends('layouts.app')

@section('content')
<div class="container">
    <h2>{{$user->name}}</h2>
    <div style="margin:0 0 20px;">
        <span style="font-weight:bold">{{$user->follows_count}}</span>
        <a href="{{ route('user.follows', ['id' => $user->id]) }}">フォロー</a>

        <span style="font-weight:bold">{{$user->followers_count}}</span>
        <a href="{{ route('user.followers', ['id' => $user->id]) }}">フォロワー</a>

        @if ($user->id !== Auth::user()->id)
            @if ($user->followed_by_user)
                <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit"  style="background:deepskyblue;color:white;">
                        フォロー中
                    </button>
                </form>
            @else
                <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" >
                        フォロー
                    </button>
                </form>
            @endif      
        @endif
    </div>
    <div>
        @yield('information')
    </div>
</div>
@endsection