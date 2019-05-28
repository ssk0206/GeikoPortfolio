@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <table class="table table-striped">
            <h4>全ユーザーリスト</h4>
            <tr>
                <th>ユーザー名</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td><a href="{{ route('users.show', ['id' => $user->id]) }}" class="center">{{$user->name}}</a></td>
                </tr>
            @endforeach
            {{ $users->links() }}
        </table>
    </div> 
@endsection
