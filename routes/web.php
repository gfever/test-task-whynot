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

Route::get('/', 'PostController@index');

Auth::routes();
Route::resource('posts', 'PostController');
/** @see \App\Http\Controllers\PostController::userIndex() */
Route::get('/my/posts', 'PostController@userIndex');
/** @see \App\Http\Controllers\PostController::publishToggle() */
Route::post('/posts/{post}/publishToggle', 'PostController@publishToggle');