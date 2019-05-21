@extends('users.show_parent')

@section('information')
    <div class="col-md-6">
        <table class="table table-striped">
            <h4>ユーザー一覧</h4>
            <tr>
                <th>ユーザー名</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>
                        <a href="/users/{{ $user->id }}">
                            <span style="text-align:center;">{{$user->name}}</span>                
                        </a>
                    </td>
                </tr>
            @endforeach
            {{ $users->links() }}
        </table>
    </div> 
@endsection
