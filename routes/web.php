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
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UsersController@create')->name('signup');
Route::resource('users', 'UsersController');
/*Route::get('/users', 'UsersController@index')->name('users.index');
Route::get('/users/{id}', 'UsersController@show')->name('users.show');
Route::get('/users/create', 'UsersController@create')->name('users.create');
Route::post('/users', 'UsersController@store')->name('users.store');
Route::get('/users/{id}/edit', 'UsersController@edit')->name('users.edit');
Route::patch('/users/{id}', 'UsersController@update')->name('users.update');
Route::delete('/users/{id}', 'UsersController@destroy')->name('users.destroy');*/
Route::get('sigup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
Route::get('/users/{id}/followings', 'UsersController@followings')->name('users.followings');
Route::get('/users/{id}/followers', 'UsersController@followers')->name('users.followers');
Route::post('/users/followers/{id}', 'FollowersController@store')->name('followers.store');
Route::delete('/users/followers/{id}', 'FollowersController@destroy')->name('followers.destroy');

Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');

Route::get('password/email', 'Auth\ForgotPasswordController@getEmail')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@postEmail')->name('password.reset');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@getReset')->name('password.edit');
Route::post('password/reset', 'Auth\ResetPasswordController@postReset')->name('password.update');

Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
