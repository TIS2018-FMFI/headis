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
Route::get('/users/{id}', 'UserController@show');
Route::post('/users/{id}/destroy', 'UserController@destroy');
Route::post('/users/{id}/update', 'UserController@update');

Route::get('/challenges/{id}', 'ChallengeController@show');
Route::post('/challenges/store', 'ChallengeController@store');

Route::post('/matches/{id}/edit', 'MatchController@edit');
Route::post('/matches/{id}/update', 'MatchController@update');

Route::post('/comments/store', 'CommentController@store');

Route::post('/dates/store', 'DateController@store');
Route::post('/dates/{id}/destroy', 'DateController@destroy');

Route::post('/sets/store', 'SetController@store');
Route::post('/sets/{id}/update', 'SetController@update');

Route::get('/pyramids', 'PyramidController@index')->name('pyramids');

Route::get('/posts/{id}', 'PostController@show');
Route::post('/posts/{id}/edit', 'PostController@edit');
Route::post('/posts/{id}/update', 'UserController@update');

Route::get('/managers', 'ManagerController@index')->name('managers');
Route::post('/managers/store', 'ManagerController@store');