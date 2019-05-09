<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes(['verify' => true]);
Route::get('/home', 'HomeController@index')->name('home');

// 一覧表示(現在はhome.index)
Route::get('/', 'HomeController@index');

// 一覧表示
Route::get('/fileindex', 'FileController@index');

// 詳細表示
Route::get('/files/{id}', 'FileController@show')->name('file.show');

// ユーザーページ
Route::get('/users/{id}', 'UserController@show')->name('user.show');
Route::get('/users/{id}/follows', 'UserController@follows')->name('user.follows');
Route::get('/users/{id}/followers', 'UserController@followers')->name('user.followers');


Route::middleware('verified')->group(function() {

    // 投稿機能
    Route::get('/create', 'FileController@showCreateForm')->name('file.form');
    Route::post('/create', 'FileController@create')->name('file.create');

    // コメント追加
    Route::post('/files/{file}/comments', 'FileController@addComment')->name('file.comment');

    // いいね / 解除
    Route::put('/files/{id}/like', 'FileController@like')->name('file.like');
    Route::delete('/files/{id}/like', 'FileController@unlike');

    // フォロー / 解除
    Route::put('/users/{id}/follow', 'UserController@follow')->name('user.follow');
    Route::delete('/users/{id}/follow', 'UserController@unfollow');

    // 本登録ユーザーだけ表示できるページ
    Route::get('verified',  function(){

        return '本登録が完了してます！';

    });

});
