<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-intimations']], function () {
    Route::group(['prefix' => 'intimations'], function () {
        Route::any('/index/{slug}', 'IntimationController@index')->name('intimations.index');
        Route::any('/show/{id}', 'IntimationController@show')->name('intimations.show');
        Route::get('/permanent-delete/{id}', 'IntimationController@permanentDelete')->name('intimations.permanent-delete')->middleware('permission:delete-intimations');
        Route::post('/academic-record/update', 'IntimationController@updateAcademicRecord')->name('intimations.acadmic-record.update');
        Route::post('/senior-lawyer/update', 'IntimationController@updateSeniorLawyer')->name('intimations.senior-lawyer.update');
        Route::post('/intimation-date/update', 'IntimationController@updateIntimationDate')->name('intimations.intimation-date.update');
        Route::any('/status/{id}', 'IntimationController@status')->name('intimations.status');
        Route::post('/status-update', 'IntimationController@updateStatus')->name('intimations.status.update');
        Route::any('/print-detail', 'IntimationController@print')->name('intimations.print-detail');
        Route::any('/pdf', 'IntimationController@pdf')->name('intimations.pdf');
        Route::any('/token', 'IntimationController@token')->name('intimations.token');
        Route::get('/Habib-Bank-Limited-Voucher', 'IntimationController@habibBankLimitedVoucher')->name('intimations.habib-bank-limited-voucher');
        Route::post('/notes', 'IntimationController@notes')->name('intimations.notes');
        Route::post('/reports/pdf', 'IntimationController@reportPdf')->name('intimations.reports.pdf');
        Route::any('/export/form-b', 'IntimationController@exportFormB')->name('intimations.export.form-b');
        Route::any('/acct-dept-payment-status', 'IntimationController@acctDeptPaymentStatus')->name('intimations.acct-dept-payment-status');
        Route::post('/objections/{id}', 'IntimationController@objections')->name('intimations.objections');

        // CREATE AND EDIT INTIMATION
        Route::group(['middleware' => ['permission:add-intimations']], function () {
            Route::any('/registerUser', 'IntimationController@registerUser')->name('intimations.registerUser');
            Route::any('/create/{id}', 'IntimationController@createStep1')->name('intimations.create-step-1');
            Route::any('/create/step/2/{id}', 'IntimationController@createStep2')->name('intimations.create-step-2');
            Route::any('/create/step/3/{id}', 'IntimationController@createStep3')->name('intimations.create-step-3');
            Route::any('/create/step/4/{id}', 'IntimationController@createStep4')->name('intimations.create-step-4');
            Route::any('/create/step/5/{id}', 'IntimationController@createStep5')->name('intimations.create-step-5');
            Route::any('/create/step/6/{id}', 'IntimationController@createStep6')->name('intimations.create-step-6');
            Route::any('/create/step/7/{id}', 'IntimationController@createStep7')->name('intimations.create-step-7');
            Route::any('/sendOTP/{id}', 'IntimationController@sendOTP')->name('intimations.sendOTP');
            Route::group(['prefix' => 'uploads'], function () {
                Route::any('/profile-image', 'IntimationController@uploadProfileImage')->name('intimations.uploads.profile-image');
                Route::any('/cnic-front', 'IntimationController@uploadCnicFront')->name('intimations.uploads.cnic-front');
                Route::any('/cnic-back', 'IntimationController@uploadCnicBack')->name('intimations.uploads.cnic-back');
                Route::any('/srl-cnic-front', 'IntimationController@uploadSrlCnicFront')->name('intimations.uploads.srl-cnic-front');
                Route::any('/srl-cnic-back', 'IntimationController@uploadSrlCnicBack')->name('intimations.uploads.srl-cnic-back');
                Route::any('/srl-license-front', 'IntimationController@uploadSrlLicenseFront')->name('intimations.uploads.srl-license-front');
                Route::any('/srl-license-back', 'IntimationController@uploadSrlLicenseBack')->name('intimations.uploads.srl-license-back');
            });
            Route::group(['prefix' => 'destroy'], function () {
                Route::any('/academic-record/{id}', 'IntimationController@destroyAcademicRecord')->name('intimations.destroy.academic-record');
                Route::any('/profile-image', 'IntimationController@destroyProfileImage')->name('intimations.destroy.profile-image');
                Route::any('/cnic-front', 'IntimationController@destroyCnicFront')->name('intimations.destroy.cnic-front');
                Route::any('/cnic-back', 'IntimationController@destroyCnicBack')->name('intimations.destroy.cnic-back');
                Route::any('/srl-cnic-front', 'IntimationController@destroySrlCnicFront')->name('intimations.destroy.srl-cnic-front');
                Route::any('/srl-cnic-back', 'IntimationController@destroySrlCnicBack')->name('intimations.destroy.srl-cnic-back');
                Route::any('/srl-license-front', 'IntimationController@destroySrlLicenseFront')->name('intimations.destroy.srl-license-front');
                Route::any('/srl-license-back', 'IntimationController@destroySrlLicenseBack')->name('intimations.destroy.srl-license-back');
            });
        });

        // INTIMATION PAYMENT
        Route::group(['middleware' => ['permission:add-payments']], function () {
            Route::any('/payment/{id}', 'IntimationController@payment')->name('intimations.payment');
            Route::any('/payment-voucher', 'IntimationController@uploadPaymentVoucher')->name('intimations.payment.voucher');
            Route::any('/destroy-voucher', 'IntimationController@destroyPaymentVoucher')->name('intimations.destroy.voucher');
            Route::any('/delete-payment', 'IntimationController@deletePayment')->name('intimations.delete-payment');
        });

        // INTIMATION RCPT
        // Route::prefix('/rcpt-date')->group(function () {
        //     Route::get('/create/{id}', 'IntimationController@createRcptDate')->name('intimations.rcpt-date.create');
        //     Route::post('/update', 'IntimationController@updateRcptDate')->name('intimations.rcpt-date.update');
        // });
        Route::any('rcpt-date', 'IntimationController@rcptDate')->name('intimations.rcpt-date');
    });
});
