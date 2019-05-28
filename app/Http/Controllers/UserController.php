<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\File;
use App\Relationship;
use App\Http\Requests\StoreUserInformation;

class UserController extends Controller
{
    public function __construct()
    {
        //認証が必要
        $this->middleware('auth');
    }


    /**
     * ユーザー一覧
     */
    public function index()
    {
        $users = User::orderBy(File::CREATED_AT, 'desc')->paginate(20);
        return view('users.index', ['users' => $users]);
    }

    /**
     * マイページ表示
     */
    public function show(int $id)
    {
        $files = File::where('user_id', $id)->orderBy(File::CREATED_AT, 'desc')->paginate(12);
        $user = '';
        if (count($files) > 0) {
            $user = $files[0]->user;
        } else {
            $user = User::findOrFail($id);
        }

        return view('users.show', ['user' => $user, 'files' => $files]);
    }

    /**
     * マイページ編集画面
     */
    public function edit(int $id)
    {
        $user = User::findOrFail($id);
        $departments = config('univ.department');
        $grades = config('univ.grade');

        return view('users.edit', ['user' => $user, 'departments' => $departments, 'grades' => $grades]);
    }

    /**
     * マイベージ編集
     */
    public function update(StoreUserInformation $request, int $id)
    {
        $user = User::findOrFail($id);
 
        $user->name = $request->name;
        $user->grade = $request->grade;
        $user->department = $request->department;
        $user->skill = $request->skill;
        $user->self_introduction = $request->self_introduction;
        $user->save();

        return redirect()->route('users.show', ['id' => $user->id]);
    }

    /**
     * フォロー
     */
    public function follow(int $id)
    {
        $user = User::with(['follows', 'followers'])->findOrFail($id);

        if ($user->id === Auth::user()->id) {
            return redirect()->route('users.show', ['id' => $user->id]);
        }

        $user->followers()->detach(Auth::user()->id);
        $user->followers()->attach(Auth::user()->id);

        return redirect()->route('users.show', ['id' => $user->id]);
    }

    /**
     * フォロー解除
     */
    public function unfollow(int $id)
    {
        $user = User::with(['follows', 'followers'])->findOrFail($id);
        $user->followers()->detach(Auth::user()->id);

        return redirect()->route('users.show', ['id' => $user->id]);
    }

    /**
     * フォローユーザー表示
     */
    public function follows(int $id)
    {
        $user = User::with(['follows'])->findOrFail($id);
        
        return view('users.follows', ['user' => $user]);
    }

    /**
     * フォロワーユーザー表示
     */
    public function followers(int $id)
    {
        $user = User::with(['followers'])->findOrFail($id);

        return view('users.followers', ['user' => $user]);
    }

    /**
     * ユーザー退会
     */
    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->to('/')->with('message', '退会が完了しました。');
    }
}
