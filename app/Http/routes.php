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

/**
 * Display All Companies
 */
Route::get('/', function () {
	return view('companies');
});

/**
 * Add A New Company
 */
Route::post('/company', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'name' => 'required|max:5',
    ]);

    if ($validator->fails()) {
        return redirect('/')
            ->withInput()
            ->withErrors($validator);
    }

    // Create The Company...
});

/**
 * Delete An Existing Company
 */
Route::delete('/company/{id}', function ($id) {
    //
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


Route::get('dashboard', function () {
	$user = new \App\User(array('name' => 'John'));
    return view('dashboard')->with('user', $user);
});

// Using A Route Closure...

Route::get('profile', ['middleware' => 'auth', function() {
	// Only authenticated users may enter...
	return view('profile'); 
}]);
Route::get('profile', [
    'middleware' => 'auth',
    'uses' => 'ProfileController@show'
]);

// Using A Controller...

Route::get('profile', [
    'middleware' => 'auth',
    'uses' => 'ProfileController@show'
]);
