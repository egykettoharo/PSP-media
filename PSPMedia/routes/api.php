<?php

use Illuminate\Http\Request;

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

Route::post('/customer/create',     'CustomerController@create');
Route::post('/customer/edit',       'CustomerController@edit');
Route::post('/deposit/add',         'CustomerController@addDeposit');
Route::post('/withdraw/add',        'CustomerController@addWithdraw');
Route::get('/report',               'CustomerController@report');
//Route::middleware('auth:api')->get('/user', function () {
//    var_dump('asdsadad');
//});
