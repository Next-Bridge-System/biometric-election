<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Intimation payments
Route::post('/get-voucher', 'API\VoucherController@getVoucher');
Route::post('/pay-voucher', 'API\VoucherController@payVoucher');

// Lower court payments
Route::group(['prefix' => 'lower-court'], function () {
    Route::group(['prefix' => 'get-voucher'], function () {
        Route::post('pbc-enr', 'API\LowerCourtPaymentController@getEnr');
        Route::post('pbc-gi', 'API\LowerCourtPaymentController@getGi');
        Route::post('pbc-bf', 'API\LowerCourtPaymentController@getBf');
        Route::post('pbc-plj', 'API\LowerCourtPaymentController@getPlj');
    });
    Route::group(['prefix' => 'pay-voucher'], function () {
        Route::post('pbc-enr', 'API\LowerCourtPaymentController@payEnr');
        Route::post('pbc-gi', 'API\LowerCourtPaymentController@payGi');
        Route::post('pbc-bf', 'API\LowerCourtPaymentController@payBf');
        Route::post('pbc-plj', 'API\LowerCourtPaymentController@payPlj');
    });
});

// // High court payments
// Route::group(['prefix' => 'high-court'], function () {
//     Route::group(['prefix' => 'get-voucher'], function () {
//         // Route::post('enrollment-fee-pakistan-bar', 'API\VoucherController@hcGetEnrollmentFeePakistanBar');
//         Route::post('enrollment-fee-punjab-bar', 'API\VoucherController@hcGetEnrollmentFeePunjabBar');
//         // Route::post('general-fund', 'API\VoucherController@hcGetGeneralFund');
//         Route::post('group-scheme-fee', 'API\VoucherController@hcGetGroupSchemeFee');
//         Route::post('plj-law-site-fee', 'API\VoucherController@hcGetPljLawSite');
//     });
//     Route::group(['prefix' => 'pay-voucher'], function () {
//         // Route::post('enrollment-fee-pakistan-bar', 'API\VoucherController@hcPayEnrollmentFeePakistanBar');
//         Route::post('enrollment-fee-punjab-bar', 'API\VoucherController@hcPayEnrollmentFeePunjabBar');
//         // Route::post('general-fund', 'API\VoucherController@hcPayGeneralFund');
//         Route::post('group-scheme-fee', 'API\VoucherController@hcPayGroupSchemeFee');
//         Route::post('plj-law-site-fee', 'API\VoucherController@hcPayPljLawSite');
//     });
// });

Route::post('lawyer', 'API\VerificationController@lawyer');
Route::post('high-court/search-application', 'API\HighCourtController@searchApplication');
