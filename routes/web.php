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

Route::get('/api/ru/clients','ClientsController@index');
Route::get('/api/ru/client/{id}','ClientsController@show');
Route::post('/api/ru/client','ClientsController@store');
Route::put('/api/ru/client/{id}','ClientsController@update');
Route::delete('/api/ru/client/{id}','ClientsController@delete');
