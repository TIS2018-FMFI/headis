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


Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::get('/users', 'UserController@index')->name('users');
Route::get('/users/{user}', 'UserController@show');
Route::post('/users/{user}/destroy', 'UserController@destroy');
Route::post('/users/{user}/update', 'UserController@update');

Route::get('/challenges/{challenge}', 'ChallengeController@show');
Route::post('/challenges/store', 'ChallengeController@store');

Route::post('/matches/{match}/edit', 'MatchController@edit');
Route::post('/matches/{match}/update', 'MatchController@update');

Route::post('/comments/store', 'CommentController@store');

Route::post('/dates/store', 'DateController@store');
Route::post('/dates/{date}/destroy', 'DateController@destroy');

Route::post('/sets/store', 'SetController@store');
Route::post('/sets/{set}/update', 'SetController@update');

Route::get('/pyramid', 'PyramidController@index')->name('pyramid');

Route::get('/posts', 'PostController@index');
Route::get('/posts/{post}', 'PostController@show');
Route::get('/posts/{post}/edit', 'PostController@edit');
Route::post('/posts/{post}/update', 'UserController@update');

Route::get('/managers', 'ManagerController@index')->name('managers');
Route::post('/managers/store', 'ManagerController@store');