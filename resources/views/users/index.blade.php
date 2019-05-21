@extends('users.show_parent')

@section('information')
    <div class="col-sm-12">
        <p>ユーザー一覧</p>
        @foreach ($users as $user)
            <div style="display:inline-block;margin:10px;">
                <a href="/users/{{ $user->id }}">
                    <p style="text-align:center;">{{$user->name}}</p>                    
                </a>
            </div>
        @endforeach
        {{ $users->links() }}
    </div> 
@endsection
