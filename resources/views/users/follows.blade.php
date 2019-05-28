@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <table class="table table-striped">
            <h4>フォローリスト</h4>
            <tr>
                <th>ユーザー名</th>
            </tr>
            @foreach ($user->follows as $follow)
                <tr>  
                    <td><a href="{{ route('users.show', ['id' => $follow->id]) }}">{{ $follow->name }}</a></td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection