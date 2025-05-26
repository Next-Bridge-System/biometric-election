<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-applications']], function () {
    Route::group(['prefix' => 'applications'], function () {
        Route::any('/', 'ApplicationController@index')->name('applications.index')->middleware('permission:manage-applications');;
        Route::get('/create', 'ApplicationController@create')->name('applications.create')
            ->middleware('permission:add-applications');
        Route::post('/store', 'ApplicationController@store')->name('applications.store')
            ->middleware('permission:add-applications');
        Route::get('/edit/{id}', 'ApplicationController@edit')->name('applications.edit')
            ->middleware('permission:edit-applications');
        Route::post('/update/{id}', 'ApplicationController@update')->name('applications.update')
            ->middleware('permission:edit-applications');
        Route::get('/show/{id}', 'ApplicationController@show')->name('applications.show')
            ->middleware('permission:manage-applications');
        Route::get('/destroy/{id}', 'ApplicationController@destroy')->name('applications.destroy')
            ->middleware('permission:delete-applications');
        Route::get('/pdf-view', 'ApplicationController@pdfView')->name('applications.pdf-view')
            ->middleware('permission:manage-applications');
        Route::get('/print/{id}', 'ApplicationController@print')->name('applications.print')
            ->middleware('permission:manage-applications');
        Route::get('/preview/{id}', 'ApplicationController@preview')->name('applications.preview')
            ->middleware('permission:manage-applications');
        Route::post('/final-submission', 'ApplicationController@finalSubmission')->name('applications.final-submission')
            ->middleware('permission:add-applications');
        Route::any('/import', 'ApplicationController@import')->name('applications.import')
            ->middleware('permission:add-applications');
        Route::any('/export', 'ApplicationController@export')->name('applications.export')
            ->middleware('permission:manage-applications');
        Route::any('/upload-profile-images', 'ApplicationController@uploadProfileImages')->name('applications.upload-profile-images')->middleware('permission:add-applications');
        Route::any('/unapproved', 'ApplicationController@unapproved')->name('applications.unapproved')
            ->middleware('permission:manage-applications');
        Route::get('/certificate', 'ApplicationController@certificate')->name('applications.certificate');
        Route::get('/print-address', 'ApplicationController@printAddress')->name('applications.print-address');
        Route::get('/print-address-count', 'ApplicationController@printAddressCount')->name('applications.print-address-count');
        Route::post('/reports/pdf', 'ApplicationController@reportPdf')->name('applications.reports.pdf');
        Route::any('/vp-listing', 'ApplicationController@vpIndex')->name('applications.vpIndex');
        Route::post('/vp-listing/add-queue', 'ApplicationController@addQueue')->name('applications.addQueue');
        Route::post('/vp-listing/select-all', 'ApplicationController@selectAll')->name('applications.selectAll');
        Route::post('/vp-listing/remove-queue', 'ApplicationController@removeQueue')->name('applications.removeQueue');
        Route::any('/vp-listing/bulk-export/', 'ApplicationController@exportBulk')->name('applications.exportBulk');
        Route::any('/vp-listing/bulk-export/pdf', 'ApplicationController@exportPDFs')->name('applications.exportPDFs');
        Route::any('/vp-listing/bulk-export/print', 'ApplicationController@exportPrint')->name('applications.exportPrint');
        Route::any('/vp-listing/bulk-export/excel', 'ApplicationController@exportExcel')->name('applications.exportExcel');
    });
    Route::group(['prefix' => 'secure-card', 'as' => 'secure-card.'], function () {
        Route::any('/lower-court', 'SecureCardController@index')->name('lower-court')->middleware('permission:manage-applications');
        Route::any('/renewal-lower-court', 'SecureCardController@index')->name('renewal-lower-court')->middleware('permission:manage-applications');
        Route::any('/higher-court', 'SecureCardController@index')->name('higher-court')->middleware('permission:manage-applications');
        Route::any('/renewal-higher-court', 'SecureCardController@index')->name('renewal-higher-court')->middleware('permission:manage-applications');
        Route::any('/select-all', 'SecureCardController@selectAll')->name('select-all');
        Route::any('/queue-import', 'SecureCardController@queueImport')->name('queue-import');
        Route::get('/printing-details/{id}', 'SecureCardController@printingDetails')->name('printing-details');

        Route::any('/vpp-number-import', 'SecureCardController@vppNumberImport')->name('vpp-number-import');
        Route::any('/vpp-return-import', 'SecureCardController@vppReturnImport')->name('vpp-return-import');
        Route::any('/vpp-return-back-print', 'SecureCardController@vppReturnBackPrint')->name('vpp-return-back-print');
        Route::any('/vpp-return-back-status', 'SecureCardController@vppReturnBackStatus')->name('vpp-return-back-status');

        Route::group(['prefix' => 'queue', 'as' => 'queue.'], function () {
            Route::any('/lower-court', 'SecureCardController@vpIndex')->name('lower-court');
            Route::any('/renewal-lower-court', 'SecureCardController@vpIndex')->name('renewal-lower-court');
            Route::any('/higher-court', 'SecureCardController@vpIndex')->name('higher-court');
            Route::any('/renewal-higher-court', 'SecureCardController@vpIndex')->name('renewal-higher-court');
        });
        Route::group(['prefix' => 'export', 'as' => 'export.'], function () {
            Route::any('/', 'SecureCardController@exportBulk')->name('bulk');
            Route::any('/pdf', 'SecureCardController@exportPDFs')->name('pdfs');
            Route::any('/print', 'SecureCardController@exportPrint')->name('print');
            Route::any('/excel', 'SecureCardController@exportExcel')->name('excel');
        });
    });
});
