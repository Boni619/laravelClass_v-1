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

Route::get('/', function () {
  return view('welcome');
});

Route::get('/test', function () {
  dd('test');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/testhome', 'DemoController@index')->name('new-home');
Route::resource('demo', 'DemoController');



Route::group(['namespace' => 'Auth'], function () {

  Route::get('/', ['as' => 'login-form', 'uses' => 'LoginController@showLoginForm']);
  Route::get('/login', ['as' => 'login-form', 'uses' => 'LoginController@showLoginForm']);
  Route::get('/register', ['as' => 'register', 'uses' => 'RegisterController@showRegistrationForm']);
  Route::post('/register', ['as' => 'post-register', 'uses' => 'RegisterController@register']);
  Route::post('/login', ['as' => 'login', 'uses' => 'LoginController@login']);
  Route::post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

  // Password Reset Routes...
  Route::post('password/email', ['as' => 'password.email', 'uses' => 'ForgotPasswordController@sendResetLinkEmail']);
  Route::get('password/reset', ['as' => 'password.request', 'uses' => 'ForgotPasswordController@showLinkRequestForm']);
  Route::post('password/reset', ['as' => '', 'uses' => 'ResetPasswordController@reset']);
  Route::get('password/reset/{token}', ['as' => 'password.reset', 'uses' => 'ResetPasswordController@showResetForm']);
});
