<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/create-user','App\Http\Controllers\UserController@createUser')->name('create-user-api');

Route::post('/do_login', 'App\Http\Controllers\LoginController@do_login')->name('do_login');



Route::put('/update-role','App\Http\Controllers\EmployerController@update')->name('update-role-api');

Route::delete('/delete-employer','App\Http\Controllers\EmployerController@destroy')->name('delete-employer-api');

 
Route::post('/register', 'App\Http\Controllers\UserController@register')->name("register-api");
