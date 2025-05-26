<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\SignatureController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/print-test', 'EpsonController@printTest')->name('epson.printTest');

Route::post('/uploadCameraImage', 'BiometricController@uploadCameraImage')->name('biometrics.uploadCameraImage');
Route::any('/getBiometricCount', 'BiometricController@getBiometricCount')->name('biometrics.getBiometricCount');

require __DIR__ . '/frontend.php';
require __DIR__ . '/lawyer-requests.php';
require __DIR__ . '/complaint.php';

/**
 *****************************************************************************
 ************************** ADMIN PANEL ROUTES *******************************
 *****************************************************************************
 */

// Route::group(['middleware' => 'prevent-back-history'], function() {

Route::get('/dashboard', function () {
    if (Auth::guard('admin')->check()) {
        return redirect()->route('admin.dashboard');
    }
    return view('admin.login');
});

Route::prefix('admin')->group(function () {
    Route::match(['get', 'post'], '/login', 'AdminController@login')->name('admin.login');
    Route::group(['middleware' => ['adminCheckSuspend']], function () {
        Route::group(['middleware' => ['admin']], function () {

            Route::get('/dashboard', 'AdminController@dashboard')->name('admin.dashboard');
            Route::get('/logout', 'AdminController@logout')->name('admin.logout');
            Route::any('/change-password', 'AdminController@changePassword')->name('admin.change-password');
            Route::any('/settings', 'AdminController@settings')->name('admin.settings');
            Route::post('/check-password', 'AdminController@checkPassword')->name('admin.check-password');
            Route::post('/update-password', 'AdminController@updatePassword')->name('admin.update-password');

            Route::group(['middleware' => ['default-password']], function () {
                Route::group(['middleware' => ['permission:manage-operators']], function () {
                    Route::group(['prefix' => 'admin-users'], function () {
                        Route::get('/', 'AdminUserController@index')->name('admins.index')
                            ->middleware('permission:manage-operators');
                        Route::get('/create', 'AdminUserController@create')->name('admins.create')
                            ->middleware('permission:add-operators');
                        Route::post('/store', 'AdminUserController@store')->name('admins.store')
                            ->middleware('permission:add-operators');
                        Route::get('/edit/{id}', 'AdminUserController@edit')->name('admins.edit')
                            ->middleware('permission:edit-operators');
                        Route::post('/update/{id}', 'AdminUserController@update')->name('admins.update')
                            ->middleware('permission:edit-operators');
                        Route::get('/audit/{id}', 'AdminUserController@audit')->name('admins.audit');
                        // ->middleware('permission:audit-operators');
                    });
                });

                // include 'routes/admin/application.php';
                require __DIR__ . '/admin/application.php';
                require __DIR__ . '/admin/secure-card.php';

                Route::group(['middleware' => ['permission:manage-universities']], function () {
                    Route::group(['prefix' => 'universities'], function () {
                        Route::get('/', 'UniversityController@index')->name('universities.index')
                            ->middleware('permission:manage-universities');
                        Route::get('/unapproved', 'UniversityController@unapproved')->name('universities.unapproved')
                            ->middleware('permission:manage-universities');
                        Route::get('/create', 'UniversityController@create')->name('universities.create')
                            ->middleware('permission:add-universities');
                        Route::post('/store', 'UniversityController@store')->name('universities.store')
                            ->middleware('permission:add-universities');
                        Route::get('/edit/{id}', 'UniversityController@edit')->name('universities.edit')
                            ->middleware('permission:edit-universities');
                        Route::post('/update/{id}', 'UniversityController@update')->name('universities.update')
                            ->middleware('permission:edit-universities');
                        Route::get('/show/{id}', 'UniversityController@show')->name('universities.show')
                            ->middleware('permission:manage-universities');
                        Route::get('/destroy/{id}', 'UniversityController@destroy')->name('universities.destroy')
                            ->middleware('permission:delete-universities');
                    });
                });

                Route::group(['middleware' => ['permission:manage-bars']], function () {
                    Route::group(['prefix' => 'bars'], function () {
                        Route::get('/', 'BarController@index')->name('bars.index')
                            ->middleware('permission:manage-bars');
                        Route::get('/create', 'BarController@create')->name('bars.create')
                            ->middleware('permission:add-bars');
                        Route::post('/store', 'BarController@store')->name('bars.store')
                            ->middleware('permission:add-bars');
                        Route::get('/edit/{id}', 'BarController@edit')->name('bars.edit')
                            ->middleware('permission:edit-bars');
                        Route::post('/update/{id}', 'BarController@update')->name('bars.update')
                            ->middleware('permission:edit-bars');
                        Route::get('/destroy/{id}', 'BarController@destroy')->name('bars.destroy')
                            ->middleware('permission:delete-bars');
                    });
                });

                Route::group(['middleware' => ['permission:manage-districts']], function () {
                    Route::group(['prefix' => 'divisions'], function () {
                        Route::get('/', 'DivisionController@index')->name('divisions.index')
                            ->middleware('permission:manage-districts');
                        Route::post('/store', 'DivisionController@store')->name('divisions.store')
                            ->middleware('permission:add-districts');
                        Route::post('/update', 'DivisionController@update')->name('divisions.update')
                            ->middleware('permission:edit-districts');
                        Route::get('/destroy/{id}', 'DivisionController@destroy')->name('divisions.destroy')
                            ->middleware('permission:delete-districts');
                    });
                    Route::group(['prefix' => 'districts'], function () {
                        Route::get('/', 'DistrictController@index')->name('districts.index')
                            ->middleware('permission:manage-districts');
                        Route::get('/create', 'DistrictController@create')->name('districts.create')
                            ->middleware('permission:add-districts');
                        Route::post('/store', 'DistrictController@store')->name('districts.store')
                            ->middleware('permission:add-districts');
                        Route::get('/edit/{id}', 'DistrictController@edit')->name('districts.edit')
                            ->middleware('permission:edit-districts');
                        Route::post('/update/{id}', 'DistrictController@update')->name('districts.update')
                            ->middleware('permission:edit-districts');
                        Route::get('/destroy/{id}', 'DistrictController@destroy')->name('districts.destroy')
                            ->middleware('permission:delete-districts');
                        Route::post('/tehsils/store/{id}', 'DistrictController@storeTehsil')->name('districts.tehsils.store')
                            ->middleware('permission:edit-districts');
                        Route::post('/tehsils/update', 'DistrictController@updateTehsil')->name('districts.tehsils.update')
                            ->middleware('permission:edit-districts');
                        Route::get('/tehsils/destroy/{id}', 'DistrictController@destroyTehsil')->name('districts.tehsils.destroy')
                            ->middleware('permission:delete-districts');
                    });
                });

                require __DIR__ . '/admin/intimation.php';
                require __DIR__ . '/admin/lower-court.php';
                require __DIR__ . '/admin/high-court.php';
                require __DIR__ . '/admin/report.php';
                require __DIR__ . '/admin/user.php';
                require __DIR__ . '/admin/post.php';

                Route::group(['prefix' => 'activity-log', 'as' => 'activity-log.'], function () {
                    Route::any('index/{id}', 'ActivityLogController@index')->name('index');
                    Route::any('lowerCourt/{id}', 'ActivityLogController@lowerCourtIndex')->name('lower-court-index')->middleware('permission:lower-court-activity-log');
                });

                Route::group(['middleware' => ['permission:manage-cases']], function () {
                    Route::group(['prefix' => 'cases'], function () {
                        Route::any('/create', 'LawyerCaseController@create')->name('cases.create')
                            ->middleware('permission:add-cases');
                        Route::any('/store', 'LawyerCaseController@store')->name('cases.store')
                            ->middleware('permission:add-cases');
                    });
                });

                Route::group(['middleware' => ['permission:manage-policies']], function () {
                    Route::group(['prefix' => 'policy'], function () {
                        Route::any('/', 'PolicyController@index')->name('policies.index');
                        Route::any('/create', 'PolicyController@create')->name('policies.create')
                            ->middleware('permission:add-policies');
                        Route::any('/store', 'PolicyController@store')->name('policies.store')
                            ->middleware('permission:add-policies');
                        Route::any('/edit/{id}', 'PolicyController@edit')->name('policies.edit')
                            ->middleware('permission:edit-policies');
                        Route::any('/update/{id}', 'PolicyController@update')->name('policies.update')
                            ->middleware('permission:edit-policies');
                        Route::any('/show', 'PolicyController@show')->name('policies.show')
                            ->middleware('permission:show-policies');
                    });
                });

                Route::group(['middleware' => ['permission:manage-payments']], function () {
                    Route::group(['prefix' => 'payments'], function () {
                        Route::any('/', 'PaymentController@index')->name('payments.index')->middleware('permission:manage-payments');
                        Route::any('/import', 'PaymentController@import')->name('payments.import')->middleware('permission:manage-payments');
                    });
                });


                Route::group(['middleware' => ['permission:manage-complaints']], function () {
                    Route::group(['prefix' => 'complaints'], function () {
                        Route::get('/', 'ComplaintController@index')->name('complaints.index');
                    });
                });


                // Route::group(['middleware' => ['permission:manage-biometric-verification']], function () {
                // Route::resource('/biometrics', 'BiometricController');
                Route::get('/biometrics/pdf/{id}', 'BiometricController@pdf')->name('biometrics.pdf');
                Route::get('/biometrics/election', 'BiometricController@election')->name('biometrics.election');
                Route::get('/biometrics/registration/{user_id}', 'BiometricController@registration')->name('biometrics.registration');
                Route::get('/biometrics/verification/{user_id}', 'BiometricController@verification')->name('biometrics.verification');
                Route::post('/biometrics/store', 'BiometricController@store')->name('biometrics.store');
                Route::get('/biometrics/destroy', 'BiometricController@destroy')->name('biometrics.destroy');
                // });

                Route::group(['middleware' => ['permission:manage-vouchers']], function () {
                    Route::resource('vouchers', 'VoucherController');
                    Route::post('/vouchers/payment', 'VoucherController@payment')->name('vouchers.payment');
                });

                Route::group(['middleware' => ['permission:manage-police-verification']], function () {
                    Route::post('police-verifications/changeStatus', 'PoliceVerificationController@changeStatus')
                        ->name('police-verification.changeStatus');
                    Route::get('police-verifications/exportPDF/{id}', 'PoliceVerificationController@exportPDF')
                        ->name('police-verification.exportPDF');
                    Route::resource('police-verifications', 'PoliceVerificationController');
                });

                Route::group(['middleware' => ['permission:manage-members']], function () {
                    Route::resource('members', 'MemberController')->except('destroy');
                    Route::post('destroy', 'MemberController@destroy')->name('members.destroy');
                });
            });
        });
    });
});

// });

Route::post('/getDistrictByDivision', 'DataController@getDistrictByDivision')->name('getDistrictByDivision');
Route::post('/getTehsilsByDistrict', 'DataController@getTehsilsByDistrict')->name('getTehsilsByDistrict');
Route::post('/getBarsByTehsil', 'DataController@getBarsByTehsil')->name('getBarsByTehsil');
Route::post('/getBarsByDivision', 'DataController@getBarsByDivision')->name('getBarsByDivision');

// Forgot Password Admin
Route::group(['prefix' => 'user/password'], function () {
    Route::get('/request', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('userPasswordRequest');
    Route::post('/email', 'Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('userPasswordEmail');
    Route::get('/reset/{token}', 'Auth\AdminResetPasswordController@showResetForm')->name('userPasswordReset');
    Route::post('/reset', 'Auth\AdminResetPasswordController@reset')->name('resetPasswordUser');
});

// Forgot Password Applicant
Route::group(['prefix' => 'password'], function () {
    Route::get('/request', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
});

Route::any('/otp/password-reset', 'Auth\OtpForgotPasswordController@otpPasswordReset')->name('otpPasswordReset');
Route::any('/otp/password-reset-form', 'Auth\OtpForgotPasswordController@otpPasswordResetForm')->name('otpPasswordResetForm');

// Send Messages
Route::post('sendMessage/{type?}', 'SendMessageController@sendMessage')->name('sendMessage');

// ********** TEST ROUTES ************

// Auth::routes();
// Route::get('signature', [SignatureController::class, 'index']);
// Route::post('signature', [SignatureController::class, 'upload'])->name('signature.upload');
// Route::get('print-text', [SignatureController::class, 'print']);/
// Route::get('generate-pdf', [ReportController::class, 'generateAndDownloadPdf']);
