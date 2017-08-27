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

// Creates a customer
Route::post('/customer/create',     'CustomerController@create');
// Edit a specified customer
Route::post('/customer/edit',       'CustomerController@edit');
// Add deposit to a customer
Route::post('/deposit/add',         'CustomerController@addDeposit');
// Add withdraw to a customer
Route::post('/withdraw/add',        'CustomerController@addWithdraw');
// Creates a report by from - to values
Route::get('/report',               'CustomerController@report');
