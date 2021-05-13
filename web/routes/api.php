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
Route::post('/client', 'App\Http\Controllers\ClientController@createClient');
Route::get('/client/{clientId}', 'App\Http\Controllers\ClientController@getClient');

Route::post('/account', 'App\Http\Controllers\AccountController@createAccount');
Route::get('/account/{accountId}', 'App\Http\Controllers\AccountController@getAccount');

Route::post('/transfer', 'App\Http\Controllers\TransferController@createTransfer');
