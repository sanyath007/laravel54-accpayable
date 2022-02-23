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
    /** Dashboard */
    Route::get('dashboard/card-data/{year}', 'HomeController@cardData');
    Route::get('dashboard/sum-month-chart/{year}', 'HomeController@sumMonth');     
    Route::get('dashboard/sum-year-chart/{year}', 'HomeController@sumYear'); 
    
    /** รายการตั้งหนี้ */
    Route::get('debt/list', 'DebtController@index');
    Route::get('debt/search/{dataType}/{sdate}/{edate}/{supplier}/{showall}', 'DebtController@search');
    Route::get('debt/rpt/{creditor}/{sdate}/{edate}/{showall}', 'DebtController@debtRpt');
    Route::get('debt/get-debt/{debtId}', 'DebtController@getById');
    Route::get('debt/add', 'DebtController@add');
    Route::post('debt/store', 'DebtController@store');
    Route::get('debt/edit/{debtId}', 'DebtController@edit');
    Route::put('debt/update', 'DebtController@update');
    Route::delete('debt/delete/{debtId}', 'DebtController@delete');
    Route::post('debt/setzero', 'DebtController@setZero');
    Route::get('debt/{creditor}/list', 'DebtController@supplierDebt');
    Route::put('debt/{id}/update-status', 'DebtController@updateStatus');

    /** รายการขออนุมัติเบิก-จ่าย */
    Route::get('approve/list', 'ApprovementController@index');
    Route::get('approve/search/{dataType}/{sdate}/{edate}/{searchKey}/{showall}', 'ApprovementController@search');
    Route::get('approve/get-all-bysupplier/{supplierId}', 'ApprovementController@getAllBySupplier');
    Route::get('approve/get-approve/{appId}', 'ApprovementController@getById');
    Route::get('approve/add', 'ApprovementController@add');
    Route::post('approve/store', 'ApprovementController@store');
    Route::get('approve/detail/{appId}', 'ApprovementController@detail');
    Route::get('approve/{id}/edit', 'ApprovementController@edit');
    Route::put('approve/{id}/update', 'ApprovementController@update');
    Route::delete('approve/delete/{appId}', 'ApprovementController@delete');
    Route::post('approve/cancel', 'ApprovementController@doCancel');

    /** รายการตัดจ่ายหนี้ */
    Route::get('payment/list', 'PaymentController@index');
    Route::get('payment/search/{dataType}/{sdate}/{edate}/{searchKey}/{showall}', 'PaymentController@search');
    Route::get('payment/get-payment/{appId}', 'PaymentController@getById');
    Route::get('payment/add', 'PaymentController@add');
    Route::post('payment/store', 'PaymentController@store');
    Route::get('payment/detail/{appId}', 'PaymentController@detail');
    Route::get('payment/{id}/edit', 'PaymentController@edit');
    Route::put('payment/update', 'PaymentController@update');
    Route::delete('payment/delete/{appId}', 'PaymentController@delete');

    /** บัญชี */
    Route::get('account/arrear', 'AccountController@arrear');    
    Route::get('account/arrear/{dataType}/{debttype}/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@arrearData');
    Route::get('account/creditor-paid', 'AccountController@creditorPaid');    
    Route::get('account/creditor-paid/{dataType}/{creditor}/{sdate}/{edate}/{showall}', 'AccountController@creditorPaidData');
    Route::get('account/ledger-creditors', 'AccountController@ledgerCreditors');
    Route::get('account/ledger-creditors/{dataType}/{sdate}/{edate}', 'AccountController@ledgerCreditorsData');
    Route::get('account/ledger-debttypes', 'AccountController@ledgerDebttypes');     
    Route::get('account/ledger-debttypes/{dataType}/{sdate}/{edate}', 'AccountController@ledgerDebttypesData'); 

    /** รายงาน */
    Route::get('report/debt-creditor/list', 'ReportController@debtCreditor');   
    Route::get('report/debt-creditor/{dataType}/{creditor}/{sdate}/{edate}/{showall}', 'ReportController@debtCreditorData');     
    Route::get('report/debt-debttype/list', 'ReportController@debtDebttype');
    Route::get('report/debt-debttype/{dataType}/{debttype}/{sdate}/{edate}/{showall}', 'ReportController@debtDebttypeData');
    Route::get('report/debt-chart/{creditorId}', 'ReportController@debtChart');
    Route::get('report/sum-arrear', 'ReportController@sumArrear');
    Route::get('report/sum-arrear/{sdate}/{edate}/{showall}', 'ReportController@sumArrearData');
    Route::get('report/sum-arrear/{dataType}/{sdate}/{edate}/{showall}', 'ReportController@sumArrearData'); 

    /** ข้อมูลพื้นฐาน */
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
    Route::get('debttype/json', 'DebttypeController@jsonList');

    Route::get('bankacc/list', 'BankAccountController@index');
	Route::get('bankacc/search/{searchKey}', 'BankAccountController@search');
    Route::get('bankacc/get-bankacc/{baId}', 'BankAccountController@getById');
    Route::get('bankacc/add', 'BankAccountController@add');
    Route::post('bankacc/store', 'BankAccountController@store');
    Route::get('bankacc/edit/{baId}', 'BankAccountController@edit');
    Route::put('bankacc/update', 'BankAccountController@update');
    Route::delete('bankacc/delete/{baId}', 'BankAccountController@delete');

    Route::get('bank/list', 'BankController@index');
	Route::get('bank/search/{searchKey}', 'BankController@search');
    Route::get('bank/get-bank/{bankId}', 'BankController@getById');
    Route::get('bank/add', 'BankController@add');
    Route::post('bank/store', 'BankController@store');
    Route::get('bank/edit/{bankId}', 'BankController@edit');
    Route::put('bank/update', 'BankController@update');
    Route::delete('bank/delete/{bankId}', 'BankController@delete');

    Route::get('bank-branch/list', 'BankBranchController@index');
	Route::get('bank-branch/search/{searchKey}', 'BankBranchController@search');
    Route::get('bank-branch/get-branch/{bbId}', 'BankBranchController@getById');
    Route::get('bank-branch/add', 'BankBranchController@add');
    Route::post('bank-branch/store', 'BankBranchController@store');
    Route::get('bank-branch/edit/{bbId}', 'BankBranchController@edit');
    Route::put('bank-branch/update', 'BankBranchController@update');
    Route::delete('bank-branch/delete/{bbId}', 'BankBranchController@delete');
});
