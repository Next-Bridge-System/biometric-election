<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-lower-court']], function () {
    Route::group(['prefix' => 'lower-court', 'as' => 'lower-court.'], function () {
        Route::get('/v2/index/{slug?}', 'Admin\LowerCourtController@indexv2')->name('indexv2');
        Route::any('/index/{slug?}', 'Admin\LowerCourtController@index')->name('index');
        Route::any('/show/{id}', 'Admin\LowerCourtController@show')->name('show');
        Route::any('/status/{id}', 'Admin\LowerCourtController@status')->name('status');
        Route::post('/status-update', 'Admin\LowerCourtController@updateStatus')->name('status.update');
        Route::post('/quick-update/{id}', 'Admin\LowerCourtController@update')->name('update');
        Route::post('/quick-create', 'Admin\LowerCourtController@quickCreate')->name('quick-create');
        Route::post('/lc-date/update', 'Admin\LowerCourtController@updateLcDate')->name('lc-date.update');
        Route::get('/permanent-delete/{id}', 'Admin\LowerCourtController@permanentDeleteLC')->name('permanent-delete');
        Route::get('/reset-payments/{id}', 'Admin\LowerCourtController@resetPayments')->name('reset-payments');
        Route::post('/objections/{id}', 'Admin\LowerCourtController@objections')->name('objections');
        Route::any('/plj-br/{id}', 'Admin\LowerCourtController@pljBloodRelation')->name('plj-br');
        Route::post('/exemption', 'Admin\LowerCourtController@exemption')->name('exemption');

        Route::group(['middleware' => ['permission:add-lower-court']], function () {
            Route::any('/initial-step', 'Admin\LowerCourtController@initialStep')->name('initial-step');
            Route::any('/registerUser', 'Admin\LowerCourtController@registerUser')->name('registerUser');
            Route::any('/create/{id}', 'Admin\LowerCourtController@createStep1')->name('create-step-1');
            Route::any('/create/step/2/{id}', 'Admin\LowerCourtController@createStep2')->name('create-step-2');
            Route::any('/create/step/3/{id}', 'Admin\LowerCourtController@createStep3')->name('create-step-3');
            Route::any('/create/step/4/{id}', 'Admin\LowerCourtController@createStep4')->name('create-step-4');
            Route::any('/create/step/5/{id}', 'Admin\LowerCourtController@createStep5')->name('create-step-5');
            Route::any('/create/step/6/{id}', 'Admin\LowerCourtController@createStep6')->name('create-step-6');
            Route::any('/create/step/7/{id}', 'Admin\LowerCourtController@createStep7')->name('create-step-7');
            Route::any('/sendOTP/{id}', 'Admin\LowerCourtController@sendOTP')->name('sendOTP');
            Route::any('upload/{slug}', 'Admin\LowerCourtController@uploadFile')->name('upload.file');
            Route::any('destory/{slug}', 'Admin\LowerCourtController@destroyFile')->name('destroy.file');
            Route::any('destroy/academic/{id}', 'Admin\LowerCourtController@destroyAcademicRecord')->name('destroy.academic-record');
            Route::any('create/step/4/{id}/exemption', 'Admin\LowerCourtController@createStep4Exemption')->name('create-step-4.exemption');
        });

        Route::group(['middleware' => ['permission:add-payments']], function () {
            Route::any('/payment/{id}/{voucherType}', 'Admin\LowerCourtController@payment')->name('payment');
            Route::any('/payment-voucher', 'Admin\LowerCourtController@uploadPaymentVoucher')->name('payment.voucher');
            Route::any('/destroy-voucher', 'Admin\LowerCourtController@destroyPaymentVoucher')->name('destroy.voucher');
            Route::any('/delete-payment', 'Admin\LowerCourtController@deletePayment')->name('delete-payment');
        });

        Route::post('move_application', 'Admin\LowerCourtController@moveApplication')->name('move-application');
        Route::post('/notes', 'Admin\LowerCourtController@notes')->name('notes');
        Route::post('/assign-member', 'Admin\LowerCourtController@assignMember')->name('assign-member');
        Route::post('/assign-member/update', 'Admin\LowerCourtController@updateAssignMember')->name('assign-member.update');
        Route::post('/assign-code-verification', 'Admin\LowerCourtController@assignCodeVerification')->name('assign-code-verification');
        Route::post('/number/update', 'Admin\LowerCourtController@updateNumber')->name('number.update');
        Route::any('rcpt-date', 'Admin\LowerCourtController@rcptDate')->name('rcpt-date');
        Route::post('excel/import', 'Admin\LowerCourtController@excelImport')->name('excel.import');
        Route::post('voter-member/update', 'Admin\LowerCourtController@updateVoterMember')->name('voter-member.update');
    });
});
