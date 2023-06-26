<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//user

Route::post('/createUser', 'App\Http\Controllers\ApiController@createUser');

Route::put('/updateUserByUser_id', 'App\Http\Controllers\ApiController@updateUserByUser_id');

Route::post('/loginUser', 'App\Http\Controllers\ApiController@loginUser');

Route::get('/viewUser', 'App\Http\Controllers\ApiController@viewUser');

Route::delete('/deleteAllUser', 'App\Http\Controllers\ApiController@deleteAllUser');

Route::delete('/deleteUserByUser_id', 'App\Http\Controllers\ApiController@deleteUserByUser_id');

//category

Route::post('/createCategory', 'App\Http\Controllers\ApiController@createCategory');

Route::get('/viewCategory', 'App\Http\Controllers\ApiController@viewCategory');

Route::get('/viewCategoryByUserId', 'App\Http\Controllers\ApiController@viewCategoryByUserId');

Route::get('/viewUserCategory', 'App\Http\Controllers\ApiController@viewUserCategory');

Route::get('/viewUserCategoryUser_id', 'App\Http\Controllers\ApiController@viewUserCategoryUser_id');