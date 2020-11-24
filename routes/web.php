<?php

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

Route::get('/', 'Auth\LoginController@showLogin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'web'], function() {
    /** ============= Authentication ============= */
    Route::get('/auth/login', 'Auth\LoginController@showLogin');

    Route::post('/auth/signin', 'Auth\LoginController@doLogin');

    Route::get('/auth/logout', 'Auth\LoginController@doLogout');

    Route::get('/auth/register', 'Auth\RegisterController@register');

    Route::post('/auth/signup', 'Auth\RegisterController@create');
});

Route::group(['middleware' => ['web','auth']], function () {
    Route::get('dashboard/card-data', 'HomeController@cardData');
    Route::get('dashboard/sum-month-chart/{month}', 'HomeController@sumMonth');     
    Route::get('dashboard/sum-year-chart/{month}', 'HomeController@sumYear'); 
    
    Route::get('approve/list', 'ApprovementController@index');
    Route::get('approve/search/{sdate}/{edate}/{searchKey}/{showall}', 'ApprovementController@search');
    Route::get('approve/get-all-bysupplier/{supplierId}', 'ApprovementController@getAllBySupplier');
    Route::get('approve/get-approve/{appId}', 'ApprovementController@getById');
    Route::get('approve/add', 'ApprovementController@add');
    Route::post('approve/store', 'ApprovementController@store');
    Route::get('approve/detail/{appId}', 'ApprovementController@detail');
    Route::get('approve/edit/{appId}', 'ApprovementController@edit');
    Route::put('approve/update', 'ApprovementController@update');
    Route::delete('approve/delete/{appId}', 'ApprovementController@delete');

    Route::get('payment/list', 'PaymentController@index');
    Route::get('payment/search/{sdate}/{edate}/{searchKey}/{showall}', 'PaymentController@search');
    Route::get('payment/get-payment/{appId}', 'PaymentController@getById');
    Route::get('payment/add', 'PaymentController@add');
    Route::post('payment/store', 'PaymentController@store');
    Route::get('payment/detail/{appId}', 'PaymentController@detail');
    Route::get('payment/edit/{appId}', 'PaymentController@edit');
    Route::put('payment/update', 'PaymentController@update');
    Route::delete('payment/delete/{appId}', 'PaymentController@delete');

    Route::get('account/arrear', 'AccountController@arrear');    
    Route::get('account/arrear-rpt/{debttype}/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@arrearRpt');     
    Route::get('account/arrear-excel/{debttype}/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@arrearExcel'); 
    Route::get('account/sum-arrear', 'AccountController@sumArrear'); 
    Route::get('account/sum-arrear/{sdate}/{edate}/{showall}', 'AccountController@sumArrearData'); 
    Route::get('account/creditor-paid', 'AccountController@creditorPaid');    
    Route::get('account/creditor-paid-rpt/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@creditorPaidRpt');     
    Route::get('account/creditor-paid-excel/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@creditorPaidExcel');
    Route::get('account/ledger/{sdate}/{edate}/{showall}', 'AccountController@ledger');     
    Route::get('account/ledger-excel/{sdate}/{edate}/{showall}', 'AccountController@ledgerExcel');
    Route::get('account/ledger-debttype/{sdate}/{edate}/{showall}', 'AccountController@ledgerDebttype');     
    Route::get('account/ledger-debttype-excel/{sdate}/{edate}/{showall}', 'AccountController@ledgerDebttypeExcel'); 

    Route::get('creditor/list', 'CreditorController@index');
    Route::get('creditor/search/{searchKey}', 'CreditorController@search');
    Route::get('creditor/get-creditor/{creditorId}', 'CreditorController@getById');
    Route::get('creditor/add', 'CreditorController@add');
    Route::post('creditor/store', 'CreditorController@store');
    Route::get('creditor/edit/{creditorId}', 'CreditorController@edit');
    Route::put('creditor/update', 'CreditorController@update');
    Route::delete('creditor/delete/{creditorId}', 'CreditorController@delete');

    Route::get('debttype/list', 'DebtTypeController@index');
	Route::get('debttype/search/{searchKey}', 'DebttypeController@search');
    Route::get('debttype/get-debttype/{debttypeId}', 'DebttypeController@getById');
    Route::get('debttype/add', 'DebtTypeController@add');
    Route::post('debttype/store', 'DebttypeController@store');
    Route::get('debttype/edit/{debttypeId}', 'DebttypeController@edit');
    Route::put('debttype/update', 'DebttypeController@update');
    Route::delete('debttype/delete/{debttypeId}', 'DebttypeController@delete');

    Route::get('debt/list', 'DebtController@index');
    Route::get('debt/rpt/{creditor}/{sdate}/{edate}/{showall}', 'DebtController@debtRpt');
    Route::get('debt/get-debt/{debtId}', 'DebtController@getById');
    Route::get('debt/add/{creditor}', 'DebtController@add');
    Route::post('debt/store', 'DebtController@store');
    Route::get('debt/edit/{creditor}/{debtId}', 'DebtController@edit');
    Route::put('debt/update', 'DebtController@update');
    Route::delete('debt/delete/{debtId}', 'DebtController@delete');
    Route::post('debt/setzero', 'DebtController@setZero');
    Route::get('debt/{creditor}/list', 'DebtController@supplierDebt');

    Route::get('report/debt-creditor/list', 'ReportController@debtCreditor');    
    Route::get('report/debt-creditor/rpt/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorRpt');    
    Route::get('report/debt-creditor-excel/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorExcel');     
    Route::get('report/debt-debttype/list', 'ReportController@debtDebttype');    
    Route::get('report/debt-debttype/rpt/{debtType}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeRpt');  
    Route::get('report/debt-debttype-excel/{debttype}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeExcel');
    Route::get('report/debt-chart/{creditorId}', 'ReportController@debtChart');    
});
