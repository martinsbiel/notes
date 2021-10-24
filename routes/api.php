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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::post('me', 'AuthController@me');
    Route::post('logout', 'AuthController@logout');
    Route::apiResource('note', 'NoteController');
    Route::post('note/search', 'NoteController@search');
});

Route::post('login', 'AuthController@login');
Route::post('register', 'AuthController@register');
Route::post('refresh', 'AuthController@refresh');