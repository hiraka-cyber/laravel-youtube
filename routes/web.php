<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'],function(){
    Route::resource('users', App\Http\Controllers\UsersController::class, ['only' =>['index', 'show', 'edit', 'update']]);

    // チャンネル登録/解除を追加
    Route::post('users/{user}/subscribe', 'App\Http\Controllers\UsersController@subscribe')->name('subscribe');
    Route::delete('users/{user}/unsubscribe', 'App\Http\Controllers\UsersController@unsubscribe')->name('unsubscribe');
    // 動画投稿関連
    Route::resource('movies', App\Http\Controllers\MoviesController::class, ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);

    // コメント関連
    Route::resource('comments', App\Http\Controllers\CommentsController::class, ['only' => ['store']]);

    // Good関連
    Route::resource('favorites', App\Http\Controllers\FavoritesController::class, ['only' => ['store', 'destroy']]);
});
