<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:gc_lower_court']], function () {
    Route::group(['prefix' => 'gc-lower-court', 'as' => 'gc-lower-court.'], function () {
        Route::any('/index', 'Admin\GcLowerCourtController@index')->name('index');
        Route::get('/edit/{id}', 'Admin\GcLowerCourtController@edit')->name('edit')->middleware('permission:gc_lower_court_edit');
        Route::post('/update/{id}', 'Admin\GcLowerCourtController@update')->name('update')->middleware('permission:gc_lower_court_edit');
        Route::get('/show/{id}', 'Admin\GcLowerCourtController@show')->name('show');
        Route::get('/audit/{id}', 'Admin\GcLowerCourtController@audit')->name('audit');
        Route::post('/notes', 'Admin\GcLowerCourtController@notes')->name('notes');
        Route::post('/media-upload', 'Admin\GcLowerCourtController@uploadMedia')->name('upload-media');
        Route::get('/media-delete', 'Admin\GcLowerCourtController@deleteMedia')->name('delete-media');
        Route::post('/move-to-hc', 'Admin\GcLowerCourtController@moveToHC')->name('move-to-hc');
    });
});

Route::group(['middleware' => ['permission:gc_high_court']], function () {
    Route::group(['prefix' => 'gc-high-court', 'as' => 'gc-high-court.'], function () {
        Route::any('/index', 'Admin\GcHighCourtController@index')->name('index');
        Route::get('/edit/{id}', 'Admin\GcHighCourtController@edit')->name('edit')->middleware('permission:gc_high_court_edit');
        Route::post('/update/{id}', 'Admin\GcHighCourtController@update')->name('update')->middleware('permission:gc_high_court_edit');
        Route::get('/show/{id}', 'Admin\GcHighCourtController@show')->name('show');
        Route::get('/audit/{id}', 'Admin\GcHighCourtController@audit')->name('audit');
        Route::post('/notes', 'Admin\GcHighCourtController@notes')->name('notes');
        Route::post('/media-upload', 'Admin\GcHighCourtController@uploadMedia')->name('upload-media');
        Route::get('/media-delete', 'Admin\GcHighCourtController@deleteMedia')->name('delete-media');
    });
});
