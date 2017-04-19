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

Route::get('/dir', 'DirController@index')->name('dir');
Route::get('/dir-list/{uri?}', 'DirController@getList')->where(['uri' => '[\s\S]+'])->name('dir_list');
Route::get('/dir-img-100x100/{uri}', 'DirController@img')->where(['uri' => '[\s\S]+'])->name('dir_img');
Route::get('/dir-download/{uri}', 'DirController@download')->where(['uri' => '[\s\S]+'])->name('dir_download');

Route::post('/player-play/', 'MPlayerController@playFile')->name('player_play');

Auth::routes();
Route::any('register', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
Route::any('password/email', 'HomeController@index');
Route::any('password/reset/{token}', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
