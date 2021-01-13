<?php

use Illuminate\Http\Request;
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

Route::get('/akun', '\App\Http\Controllers\login\crud@index');
Route::get('/user', '\App\Http\Controllers\login\crud@edit');
Route::post('/login', '\App\Http\Controllers\login\crud@create');
Route::post('/register', '\App\Http\Controllers\login\crud@store');
Route::post('/delete/{id}', '\App\Http\Controllers\login\crud@destroy');
