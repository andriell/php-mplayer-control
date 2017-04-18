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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index');

Route::get('/dir/{path}', 'DirController@index')
    ->where(['path' => '[\s\S]+']);

Auth::routes();
Route::any('register', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
Route::any('password/email', 'HomeController@index');
Route::any('password/reset/{token}', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
