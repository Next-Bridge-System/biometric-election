<?php

use App\Http\Controllers\Frontend\ComplaintController;
use App\Http\Controllers\Frontend\LawyerRequestController;
use Illuminate\Support\Facades\Route;

Route::match(['get', 'post'], '/login', 'Auth\AuthController@login')->name('frontend.login');
Route::match(['get', 'post'], '/register/{type?}', 'Auth\AuthController@register')->name('frontend.register');
Route::match(['get', 'post'], '/otp', 'Auth\AuthController@otp')->name('frontend.otp');
Route::match(['get', 'post'], '/resend-otp', 'Auth\AuthController@resendOtp')->name('frontend.resend-otp');
Route::match(['get', 'post'], '/logout', 'Auth\AuthController@logout')->name('frontend.logout')->middleware('frontend');

Route::get('/', function () {
    return redirect()->route('frontend.dashboard');
});

Route::get('/lawyer/dashboard', 'FrontendController@dashboard')->name('frontend.dashboard')->middleware('frontend');

Route::group(['prefix' => 'intimation', 'as' => 'frontend.intimation.', 'namespace' => 'Frontend'], function () {
    Route::any('/print', 'IntimationController@print')->name('print');
    Route::any('/pdf', 'IntimationController@pdf')->name('pdf');
    Route::any('/voucher', 'IntimationController@voucher')->name('voucher');
    Route::get('/bank-islami-voucher', 'IntimationController@bankIslamiVoucher')->name('bank-islami-voucher');
    Route::get('/Habib-Bank-Limited-Voucher', 'IntimationController@habibBankLimitedVoucher')->name('habib-bank-limited-voucher');
});

Route::group(['prefix' => 'applications'], function () {
    Route::any('/search', 'FrontendController@searchApplication')->name('frontend.search-application');
    Route::any('/renewal-high-court', 'FrontendController@renewalHighCourt')->name('frontend.renewal-high-court');
    Route::any('/view-renewal-high-court/{id}', 'FrontendController@viewRenewalHighCourt')->name('frontend.view-renewal-high-court');
    Route::any('/renewal-lower-court', 'FrontendController@renewalLowerCourt')->name('frontend.renewal-lower-court');
    Route::any('/view-renewal-lower-court/{id}', 'FrontendController@viewRenewalLowerCourt')->name('frontend.view-renewal-lower-court');
    // Route::any('/complaints', 'FrontendController@complaints')->name('frontend.complaints');
    Route::any('/visa-certificates', 'FrontendController@visaCertificates')->name('frontend.visa-certificates');
    Route::any('/characters-certificates', 'FrontendController@charactersCertificates')->name('frontend.characters-certificates');

    Route::group(['middleware' => ['frontend']], function () {
        Route::group(
            [
                'prefix' => 'intimation',
                'as' => 'frontend.intimation.',
                'namespace' => 'Frontend',
                'middleware' => 'intimation'
            ],
            function () {
                Route::any('/show/{id}', 'IntimationController@show')->name('show');
                Route::any('/create', 'IntimationController@create')->name('create');
                Route::group(['middleware' => ['accepted']], function () {
                    Route::any('/create/step/1/{id}', 'IntimationController@createStep1')->name('create-step-1');
                    Route::any('/create/step/2/{id}', 'IntimationController@createStep2')->name('create-step-2');
                    Route::any('/create/step/3/{id}', 'IntimationController@createStep3')->name('create-step-3');
                    Route::any('/create/step/4/{id}', 'IntimationController@createStep4')->name('create-step-4');
                    Route::any('/create/step/5/{id}', 'IntimationController@createStep5')->name('create-step-5');
                    Route::any('/create/step/6/{id}', 'IntimationController@createStep6')->name('create-step-6');
                });
                Route::any('/create/step/7/{id}', 'IntimationController@createStep7')->name('create-step-7');
                Route::any('/sendOTP/{id}', 'IntimationController@sendOTP')->name('sendOTP');
                Route::post('/transfer', 'IntimationController@transfer')->name('transfer');

                Route::group(['prefix' => 'uploads'], function () {
                    Route::any('/profile-image', 'IntimationController@uploadProfileImage')->name('uploads.profile-image');
                    Route::any('/cnic-front', 'IntimationController@uploadCnicFront')->name('uploads.cnic-front');
                    Route::any('/cnic-back', 'IntimationController@uploadCnicBack')->name('uploads.cnic-back');
                    Route::any('/srl-cnic-front', 'IntimationController@uploadSrlCnicFront')->name('uploads.srl-cnic-front');
                    Route::any('/srl-cnic-back', 'IntimationController@uploadSrlCnicBack')->name('uploads.srl-cnic-back');
                    Route::any('/srl-license-front', 'IntimationController@uploadSrlLicenseFront')->name('uploads.srl-license-front');
                    Route::any('/srl-license-back', 'IntimationController@uploadSrlLicenseBack')->name('uploads.srl-license-back');
                });
                Route::group(['prefix' => 'destroy'], function () {
                    Route::any('/academic-record/{id}', 'IntimationController@destroyAcademicRecord')->name('destroy.academic-record');
                    Route::any('/profile-image', 'IntimationController@destroyProfileImage')->name('destroy.profile-image');
                    Route::any('/cnic-front', 'IntimationController@destroyCnicFront')->name('destroy.cnic-front');
                    Route::any('/cnic-back', 'IntimationController@destroyCnicBack')->name('destroy.cnic-back');
                    Route::any('/srl-cnic-front', 'IntimationController@destroySrlCnicFront')->name('destroy.srl-cnic-front');
                    Route::any('/srl-cnic-back', 'IntimationController@destroySrlCnicBack')->name('destroy.srl-cnic-back');
                    Route::any('/srl-license-front', 'IntimationController@destroySrlLicenseFront')->name('destroy.srl-license-front');
                    Route::any('/srl-license-back', 'IntimationController@destroySrlLicenseBack')->name('destroy.srl-license-back');
                });
            }
        );

        Route::group(['prefix' => 'lc', 'as' => 'frontend.lower-court.'], function () {
            Route::any('/show/{id}', 'Frontend\LowerCourtController@show')->name('show');
            Route::any('upload/payment-voucher/{id}', 'Frontend\LowerCourtController@uploadPaymentVoucher')->name('upload.payment-voucher');
            Route::any('destroy/payment-voucher/{id}', 'Frontend\LowerCourtController@destroyPaymentVoucher')->name('destroy.payment-voucher');

            Route::group(['middleware' => ['accepted']], function () {
                Route::any('/create/step/1/{id?}', 'Frontend\LowerCourtController@createStep1')->name('create-step-1');
                Route::any('/create/step/2/{id}', 'Frontend\LowerCourtController@createStep2')->name('create-step-2');
                Route::any('/create/step/3/{id}', 'Frontend\LowerCourtController@createStep3')->name('create-step-3');
                Route::any('/create/step/4/{id}', 'Frontend\LowerCourtController@createStep4')->name('create-step-4');
                Route::any('/create/step/5/{id}', 'Frontend\LowerCourtController@createStep5')->name('create-step-5');
                Route::any('/create/step/6/{id}', 'Frontend\LowerCourtController@createStep6')->name('create-step-6');
                Route::any('/create/step/7/{id}', 'Frontend\LowerCourtController@createStep7')->name('create-step-7');
                Route::any('upload/{slug}', 'Frontend\LowerCourtController@uploadFile')->name('upload.file');
                Route::any('destory/{slug}', 'Frontend\LowerCourtController@destroyFile')->name('destroy.file');
                Route::any('destroy/academic/{id}', 'Frontend\LowerCourtController@destroyAcademicRecord')->name('destroy.academic-record');
                Route::any('create/step/4/{id}/exemption', 'Frontend\LowerCourtController@createStep4Exemption')->name('create-step-4.exemption');
            });
        });

        Route::group(['prefix' => 'hc', 'as' => 'frontend.high-court.'], function () {
            Route::any('/view/{id}', 'Frontend\HighCourtController@view')->name('view');
            Route::any('/show/{id}', 'Frontend\HighCourtController@show')->name('show');
            Route::any('upload/payment-voucher/{id}', 'Frontend\HighCourtController@uploadPaymentVoucher')->name('upload.payment-voucher');
            Route::any('destroy/payment-voucher/{id}', 'Frontend\HighCourtController@destroyPaymentVoucher')->name('destroy.payment-voucher');
            Route::get('move-to-hc/{user_id}', 'Frontend\HighCourtController@moveToHighCourt')->name('move-to-hc');
            Route::group(['middleware' => ['accepted']], function () {
                Route::any('/create/{id?}', 'Frontend\HighCourtController@createStep1')->name('create-step-1');
                Route::any('/create/step/2/{id}', 'Frontend\HighCourtController@createStep2')->name('create-step-2');
                Route::any('/create/step/3/{id}', 'Frontend\HighCourtController@createStep3')->name('create-step-3');
                Route::any('/create/step/4/{id}', 'Frontend\HighCourtController@createStep4')->name('create-step-4');
                Route::any('/create/step/5/{id}', 'Frontend\HighCourtController@createStep5')->name('create-step-5');
                Route::any('/create/step/6/{id}', 'Frontend\HighCourtController@createStep6')->name('create-step-6');
                Route::any('/create/step/7/{id}', 'Frontend\HighCourtController@createStep7')->name('create-step-7');
                Route::any('upload/{slug}', 'Frontend\HighCourtController@uploadFile')->name('upload.file');
                Route::any('destory/{slug}', 'Frontend\HighCourtController@destroyFile')->name('destroy.file');
            });
        });
    });
});

Route::group(['prefix' => 'lower-court/prints', 'as' => 'lower-court.prints.'], function () {
    Route::any('/token', 'Admin\PrintLowerCourtController@printToken')->name('token');
    Route::any('/form-lc', 'Admin\PrintLowerCourtController@printFormLc')->name('form-lc');
    Route::get('/bank-voucher', 'Admin\PrintLowerCourtController@printBankVoucher')->name('bank-voucher');
    Route::get('/candidate-interview/{id}', 'Admin\PrintLowerCourtController@printCandidateInterview')->name('candidate-interview');
    Route::get('/detail/{id}', 'Admin\PrintLowerCourtController@printDetail')->name('detail');
    Route::get('/short-detail', 'Admin\PrintLowerCourtController@printShortDetail')->name('short-detail');
    Route::get('/form-b/{id}', 'Admin\PrintLowerCourtController@printFormB')->name('form-b');
    Route::get('/form-affidavit/{id}', 'Admin\PrintLowerCourtController@printFormAffidavit')->name('form-affidavit');
    Route::get('/form-undertaking/{id}', 'Admin\PrintLowerCourtController@printUndertakingForm')->name('form-undertaking');
    Route::get('/form-e/{id}', 'Admin\PrintLowerCourtController@printFormE')->name('form-e');
    Route::get('/form-g/{id}', 'Admin\PrintLowerCourtController@printFormG')->name('form-g');
    Route::get('/advocate-certificate/{id}', 'Admin\PrintLowerCourtController@printAdvocateCertificate')->name('advocate-certificate');
});

Route::group(['prefix' => 'initmations/prints', 'as' => 'intimations.prints.'], function () {
    Route::get('initmations/prints/bank-voucher', 'IntimationController@printBankVoucher')->name('bank-voucher');
});

Route::group(['prefix' => 'high-court/prints', 'as' => 'high-court.prints.'], function () {
    Route::get('/bank-voucher', 'Admin\PrintHighCourtController@printBankVoucher')->name('bank-voucher');
    Route::get('/form-hc/{id}', 'Admin\PrintHighCourtController@printFormHc')->name('form-hc');
    Route::get('/detail/{id}', 'Admin\PrintHighCourtController@printDetail')->name('detail');
    Route::get('/short-detail/{id}', 'Admin\PrintHighCourtController@printShortDetail')->name('short-detail');
    Route::get('/advocate-certificate/{id}', 'Admin\PrintHighCourtController@printAdvocateCertificate')->name('advocate-certificate');
});

Route::get('/index', 'FrontendController@index')->name('frontend.index');
Route::get('lawyer-request-verification', 'FrontendController@lawyerRequestVerification')->name('frontend.lawyer-request-verification');
Route::get('lahore-bar-lawyers/{cnic_no?}', 'FrontendController@lahoreBarLawyers')->name('frontend.lahore-bar-lawyers');
Route::get('lahore-bar-lawyers-voucher/{cnic_no?}', 'FrontendController@lahoreBarLawyersVoucher')->name('frontend.lahore-bar-lawyers-voucher');

Route::get('highcourt-lawyers/{cnic_no?}', 'FrontendController@highcourtLawyers')->name('frontend.highcourt-lawyers');
