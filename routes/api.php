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

Route::group(['middleware' => 'api'], function () {
    /** รายการตั้งหนี้ */
    Route::get('tmp-debts', 'TmpDebtController@getAll');
    Route::get('tmp-debts/{id}', 'TmpDebtController@getById');
    Route::post('tmp-debts', 'TmpDebtController@store');
});
