@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <table class="table table-striped">
            <h4>フォロワーリスト</h4>
            <tr>
                <th>ユーザー名</th>
            </tr>
            @foreach ($user->followers as $follower)
                <tr>  
                    <td>
                        <a href="{{ route('user.show', ['id' => $follower->id]) }}">{{ $follower->name }}</a>         
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection