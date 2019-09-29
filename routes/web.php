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

Auth::routes();


Route::get('/', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name('users');
Route::get('/users/{user}', 'UserController@show')->name('users.show');
Route::post('/users/{user}/destroy', 'UserController@destroy');
Route::post('/users/{user}/update', 'UserController@update');
Route::get('/users/{user}/edit', 'UserController@edit');
Route::post('/users/activate', 'UserController@activate');

Route::get('/challenges/{challenge}', 'ChallengeController@show');
Route::post('/challenges/store', 'ChallengeController@store');

Route::post('/matches/store', 'MatchController@store');
Route::get('/matches/{match}', 'MatchController@show');
Route::post('/matches/{match}/update', 'MatchController@update');

Route::post('/comments/store', 'CommentController@store');

Route::post('/dates/store', 'DateController@store');
Route::post('/dates/{date}/destroy', 'DateController@destroy');

Route::post('/sets/store', 'SetController@store');
Route::post('/sets/update', 'SetController@update');
Route::post('/sets/validateSet', 'SetController@validateSet');

Route::prefix('pyramid')->group(function (){
    Route::get('/', 'PyramidController@index')->name('pyramid');
    Route::get('/season/{season}', 'PyramidController@index');
});

Route::get('/posts', 'PostController@index');
Route::post('/posts/store', 'PostController@store');
Route::get('/posts/{post}', 'PostController@show');
Route::get('/posts/{post}/edit', 'PostController@edit');
Route::post('/posts/{post}/update', 'PostController@update');

Route::get('/manager', 'ManagerController@index')->name('manager');

Route::get('/privacy_policy', 'HomeController@privacyPolicy');

Route::post('/seasons/store', 'SeasonController@store');
Route::post('/seasons/{season}/destroy', 'SeasonController@destroy');

Route::post('/not_available_dates/store', 'NotAvailableDateController@store');
Route::post('/not_available_dates/{notAvailableDate}/destroy', 'NotAvailableDateController@destroy');
