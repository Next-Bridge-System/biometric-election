<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-high-court']], function () {
    Route::group(['prefix' => 'high-court', 'as' => 'high-court.'], function () {
        Route::any('/list/{slug}', 'Admin\HighCourtController@index')->name('index');
        Route::any('/partial', 'Admin\HighCourtController@partial')->name('partial');
        Route::any('/show/{id}', 'Admin\HighCourtController@show')->name('show');
        Route::post('excel/import', 'Admin\HighCourtController@excelImport')->name('excel.import');
        Route::get('/permanent-delete/{id}', 'Admin\HighCourtController@permanentDelete')->name('permanent-delete');
        Route::any('/status/{id}', 'Admin\HighCourtController@status')->name('status');
        Route::post('/status-update', 'Admin\HighCourtController@updateStatus')->name('status.update');
        Route::post('move_application', 'Admin\HighCourtController@moveApplication')->name('move-application');
        Route::post('/notes', 'Admin\HighCourtController@notes')->name('notes');
        Route::post('/assign-member', 'Admin\HighCourtController@assignMember')->name('assign-member');
        Route::post('/assign-member/update', 'Admin\HighCourtController@updateAssignMember')->name('assign-member.update');
        Route::post('/assign-code-verification', 'Admin\HighCourtController@assignCodeVerification')->name('assign-code-verification');
        Route::post('/number/update', 'Admin\HighCourtController@updateNumber')->name('number.update');
        Route::any('rcpt-date', 'Admin\HighCourtController@rcptDate')->name('rcpt-date');
        Route::post('excel/import', 'Admin\HighCourtController@excelImport')->name('excel.import');
        Route::post('/quick-update/{id}', 'Admin\HighCourtController@update')->name('update');
        Route::post('/quick-create', 'Admin\HighCourtController@quickCreate')->name('quick-create');
        Route::post('/hc-date/update', 'Admin\HighCourtController@updateHcDate')->name('hc-date.update');
        Route::post('/lc-date/update', 'Admin\HighCourtController@updateLcDate')->name('lc-date.update');
        Route::post('/lc-exp-date/update', 'Admin\HighCourtController@updateLcExpDate')->name('lc-exp-date.update');
        Route::get('/reset-payments/{id}', 'Admin\HighCourtController@resetPayments')->name('reset-payments');
        Route::post('/objections/{id}', 'Admin\HighCourtController@objections')->name('objections');
        Route::any('/plj-br/{id}', 'Admin\HighCourtController@pljBloodRelation')->name('plj-br');

        Route::group(['middleware' => ['permission:add-high-court']], function () {
            Route::any('/initial-step', 'Admin\HighCourtController@initialStep')->name('initial-step');
            Route::any('/registerUser', 'Admin\HighCourtController@registerUser')->name('registerUser');
            Route::any('/create/{id}', 'Admin\HighCourtController@createStep1')->name('create-step-1');
            Route::any('/create/step/2/{id}', 'Admin\HighCourtController@createStep2')->name('create-step-2');
            Route::any('/create/step/3/{id}', 'Admin\HighCourtController@createStep3')->name('create-step-3');
            Route::any('/create/step/4/{id}', 'Admin\HighCourtController@createStep4')->name('create-step-4');
            Route::any('/create/step/5/{id}', 'Admin\HighCourtController@createStep5')->name('create-step-5');
            Route::any('/create/step/6/{id}', 'Admin\HighCourtController@createStep6')->name('create-step-6');
            Route::any('/create/step/7/{id}', 'Admin\HighCourtController@createStep7')->name('create-step-7');
            Route::any('/sendOTP/{id}', 'Admin\HighCourtController@sendOTP')->name('sendOTP');
            Route::any('upload/{slug}', 'Admin\HighCourtController@uploadFile')->name('upload.file');
            Route::any('destory/{slug}', 'Admin\HighCourtController@destroyFile')->name('destroy.file');
            Route::any('destroy/academic/{id}', 'Admin\HighCourtController@destroyAcademicRecord')->name('destroy.academic-record');
            Route::any('create/step/4/{id}/exemption', 'Admin\HighCourtController@createStep4Exemption')->name('create-step-4.exemption');
        });

        Route::group(['middleware' => ['permission:add-payments']], function () {
            Route::any('/payment/{id}/{voucherType}', 'Admin\HighCourtController@payment')->name('payment');
            Route::any('/payment-voucher', 'Admin\HighCourtController@uploadPaymentVoucher')->name('payment.voucher');
            Route::any('/destroy-voucher', 'Admin\HighCourtController@destroyPaymentVoucher')->name('destroy.voucher');
            Route::any('/delete-payment', 'Admin\HighCourtController@deletePayment')->name('delete-payment');
        });
    });
});
