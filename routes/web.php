<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use App\Http\Middleware\EnsureTokenIsValid;
use Firebase\JWT\JWT;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',function(){
    if(\Session::has('jwt_token')){
        $user = JWT::decode(Session::get('jwt_token'), getenv('JWT_SECRET'), array('HS256'));
        return view('admin.authenticated',['user'=>$user,'jwt'=>Session::get('jwt_token')]);
    }
    return view('admin.login',['error'=>'']);
})->name('admin-login');

Route::post('login',  'App\Http\Controllers\UserController@login')->name("login-api");
Route::get('logout', 'App\Http\Controllers\UserController@logout')->name('logout');
Route::post('/add-employer','App\Http\Controllers\EmployerController@create')->name('add-employer-api');
Route::get('/get-all-employer','App\Http\Controllers\EmployerController@index')->name('get-all-employer-api');
Route::get('/download-employer-data','App\Http\Controllers\EmployerController@downloadData')->name('download-employer-api');
Route::get('/register',function(){
    return view('admin.signup');
})->name('admin-register');

// Route::get('home/{token}', 'App\Http\Controllers\UserController@adminHome')->name('admin-home')->middleware(EnsureTokenIsValid::class);
Route::get('getEmployees', 'App\Http\Controllers\UserController@getEmployeesPage')->name('admin-employee-page');#->middleware(EnsureTokenIsValid::class);

// Route::post('/login','App\Http\Controllers\UserController@loginUser')->name('login-user-api');
Route::post('/home','App\Http\Controllers\UserController@loginUserHome')->name('admin-home');
