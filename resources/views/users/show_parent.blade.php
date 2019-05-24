@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <h2><a href="{{ route('user.show', ['id' => $user->id]) }}">{{$user->name}}</a></h2>
            <div style="margin:0 0 20px;">
                <div style="margin:0 0 10px;">
                    <span style="font-weight:bold">{{$user->follows_count}}</span>
                    <a href="{{ route('user.follows', ['id' => $user->id]) }}">フォロー</a>
        
                    <span style="font-weight:bold">{{$user->followers_count}}</span>
                    <a href="{{ route('user.followers', ['id' => $user->id]) }}">フォロワー</a>
                </div>
                <div>{{$user->grade}}</div>
                <div>{{$user->department}}</div>
                <div>{{$user->skill}}</div>
                <div>{{$user->self_introduction}}</div>
                <hr>
                <div>
                    @guest
                    @else
                    
                    @if ($user->id !== Auth::user()->id)
                        @if ($user->followed_by_user)
                            <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-info">
                                    フォロー中
                                </button>
                            </form>
                        @else
                            <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">
                                <button type="submit" class="btn btn-outline-info" >
                                    フォロー
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('user.edit', ['id' => $user->id]) }}" class="btn btn-info">
                            ユーザー情報編集
                        </a>
                    @endif
                    @endguest
                </div>
            </div>
        </div>
        @yield('information')
    </div>
</div>
@endsection