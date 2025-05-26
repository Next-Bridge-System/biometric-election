<?php

use Illuminate\Support\Facades\Route;
// Route::group(['middleware' => ['permission:secure-card']], function () {
    Route::group(['prefix' => 'secure-card'], function () {
        Route::any('/', 'SecureCardController@index')->name('secure-card.index');
        Route::get('export-images/{encoded_data}', 'SecureCardController@exportImages')->name('secure-card.export-images');
        Route::get('export-letters-envelops/{encoded_data}', 'SecureCardController@exportLettersEnvelops')->name('secure-card.export-letters-envelops');
    });
// });