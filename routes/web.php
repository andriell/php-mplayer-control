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
Route::get('/dir-list/{uri?}', 'DirController@getList')->where(['uri' => '[\s\S]+']);
Route::get('/dir-img-100x100/{uri}', 'DirController@img100x100')->where(['uri' => '[\s\S]+']);
Route::get('/dir-img-1024x768/{uri}', 'DirController@img1024x768')->where(['uri' => '[\s\S]+']);
Route::get('/dir-download/{uri}', 'DirController@download')->where(['uri' => '[\s\S]+']);
Route::post('/dir-upload/{uri?}', 'DirController@upload')->where(['uri' => '[\s\S]+']);
Route::get('/dir-only-dir/{uri?}', 'DirController@onlyDir')->where(['uri' => '[\s\S]+']);
Route::get('/dir-slide/{uri?}', 'DirController@slide')->where(['uri' => '[\s\S]+']);
Route::get('/dir-slide-left/', 'DirController@keyLeft');
Route::get('/dir-slide-right/', 'DirController@keyRight');
Route::get('/dir-slide-show/{uri?}', 'DirController@slideShow')->where(['uri' => '[\s\S]+']);
Route::get('/dir-slide-stop/', 'DirController@slideStop');
Route::post('/dir-mv/', 'DirController@mv');
Route::post('/dir-cut/', 'DirController@cut');
Route::post('/dir-copy/', 'DirController@copy');
Route::post('/dir-new-folder/', 'DirController@newFolder');
Route::post('/dir-delete/', 'DirController@doDelete');

Route::get('/player-play-video/{uri}', 'MPlayerController@playVideo')->where(['uri' => '[\s\S]+']);
Route::get('/player-pause/', 'MPlayerController@pause');
Route::get('/player-quit/', 'MPlayerController@quit');
Route::get('/player-get-volume/', 'MPlayerController@getVolume');
Route::get('/player-set-volume/{volume}', 'MPlayerController@setVolume')->where(['volume' => '[0-9]+']);
Route::get('/player-get-length/', 'MPlayerController@getLength');
Route::get('/player-get-time-pos/', 'MPlayerController@getTimePos');
Route::get('/player-set-time-pos/{timePos}', 'MPlayerController@setTimePos')->where(['timePos' => '[0-9]+']);
Route::get('/player-set-mute/{mute}', 'MPlayerController@setMute')->where(['mute' => '[tf]']);
Route::get('/player-switch-audio/', 'MPlayerController@switchAudio');
Route::get('/player-switch-video/', 'MPlayerController@switchVideo');
Route::get('/player-get-info/', 'MPlayerController@getInfo');
Route::get('/player-command/', 'MPlayerController@command');
Route::get('/player-all-property/', 'MPlayerController@getAllProperty');

Auth::routes();
Route::any('register', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
Route::any('password/email', 'HomeController@index');
Route::any('password/reset/{token}', 'HomeController@index');
Route::any('password/reset', 'HomeController@index');
