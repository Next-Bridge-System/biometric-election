<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-reports'], 'prefix' => 'reports', 'as' => 'reports.'], function () {
    Route::get('/', 'ReportController@index')->name('index');
    Route::post('/export', 'ReportController@export')->name('export');
    Route::get('/vpp', 'ReportController@vpp')->name('vpp');
    Route::post('/vpp/export', 'ReportController@vppExport')->name('vpp.export');

    Route::get('/intimation', 'ReportController@intimationIndex')->name('intimation');
    Route::group(['prefix' => 'intimation', 'as' => 'intimation.'], function () {
        Route::post('/export', 'ReportController@intimationExport')->name('export');
    });

    Route::group(['prefix' => 'lower-court', 'as' => 'lower-court.'], function () {
        Route::get('/rcpt', 'ReportController@lowerCourtRcpt')->name('rcpt');
        Route::get('/license/index', 'ReportController@indexLowerCourtLicense')->name('license.index');
        Route::post('/license/export', 'ReportController@exportLowerCourtLicense')->name('license.export');
        Route::post('/address/export', 'ReportController@exportLowerCourtAddress')->name('address.export');
    });

    Route::group(['prefix' => 'high-court', 'as' => 'high-court.'], function () {
        Route::get('/license/index', 'ReportController@indexHighCourtLicense')->name('license.index');
        Route::post('/license/export', 'ReportController@exportHighCourtLicense')->name('license.export');
    });

    Route::get('/voter-member/index', 'ReportController@indexVoterMember')->name('voter-member.index');
    Route::get('/voter-member/export', 'ReportController@exportVoterMember')->name('voter-member.export');

    Route::get('/lawyer-summary/index', 'ReportController@indexLawyerSummaryReport')->name('lawyer-summary-report.index');
    Route::get('/lawyer-summary/export', 'ReportController@exportLawyerSummaryReport')->name('lawyer-summary-report.export');

    Route::get('/general-search', 'ReportController@generalSearchReport')->name('general-search-report');
});
