<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\File;
use App\Relationship;

class UserController extends Controller
{
    /**
     * マイページ表示
     */
    public function show(int $id)
    {
        //filesのページネーションのためにはどうすればいいか
        #$user = User::where('id', $id)->with(['files'])->first();
        $files = File::where('user_id', $id)->orderBy(File::CREATED_AT, 'desc')->paginate(9);
        $user = $files[0]->user;
        return view('users.show', ['user' => $user, 'files' => $files]);
    }

    /**
     * フォロー
     */
    public function follow(int $id)
    {
        $user = User::where('id', $id)->with(['follows', 'followers'])->first();

        if (! $user) {
            abort(404);
        }

        if ($user->id === Auth::user()->id) {
            return redirect()->route('user.show', ['id' => $user->id]);
        }

        $user->followers()->detach(Auth::user()->id);
        $user->followers()->attach(Auth::user()->id);

        return redirect()->route('user.show', ['id' => $user->id]);
    }

    /**
     * フォロー解除
     */
    public function unfollow(int $id)
    {
        $user = User::where('id', $id)->with(['follows', 'followers'])->first();

        if (! $user) {
            abort(404);
        }

        $user->followers()->detach(Auth::user()->id);

        return redirect()->route('user.show', ['id' => $user->id]);
    }

    /**
     * フォローユーザー表示
     */
    public function follows(int $id)
    {
        $user = User::where('id', $id)->with(['follows'])->first();
        
        return view('users.follows', ['user' => $user]);
    }

    /**
     * フォロワーユーザー表示
     */
    public function followers(int $id)
    {
        $user = User::where('id', $id)->with(['followers'])->first();
        return view('users.followers', ['user' => $user]);
    }
}
