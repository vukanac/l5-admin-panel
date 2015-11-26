<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Company;
use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});




// Route::get('/', ['middleware' => 'auth', function () {
//     return Redirect::to('users.dashboard')->with('message', 'Login Failed');
// }]);

// Route::get('auth', ['middleware' => 'auth', function () {
//     return Redirect::to('dashboard')->with('message', 'Login Failed');
// }]);

// Authentication routes...
//Route::get('auth/login', array('as' => 'auth/login', function () { }))->before('guest');
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Password reset link request routes...
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// Password reset routes...
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

// Company routes
Route::get('/companies', 'CompanyController@index');
Route::get('/company/{company}', 'CompanyController@show');
Route::post('/company', 'CompanyController@store');
Route::delete('/company/{company}', 'CompanyController@destroy');
Route::get('/company/{company}/edit', 'CompanyController@edit');
Route::put('/company/{company}', 'CompanyController@update');

// User routes
Route::get('/users', 'UserController@index');
