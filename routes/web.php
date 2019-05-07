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

Route::get('/', 'FileController@index');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');



Route::middleware('verified')->group(function() {

    Route::get('/create', 'FileController@showCreateForm')->name('file.form');
    Route::post('/create', 'FileController@create')->name('file.create');

    // 本登録ユーザーだけ表示できるページ
    Route::get('verified',  function(){

        return '本登録が完了してます！';

    });

});
