<?php

use App\Http\Controllers\Frontend\LawyerRequestController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['admin']], function () {
    Route::group(['prefix' => 'admin/lawyer-requests', 'as' => 'lawyer-requests.', 'middleware' => ['permission:manage-lawyer-requests']], function () {
        Route::get('/sub-categories', 'Admin\LawyerRequestController@subCategoriesIndex')->name('sub-categories');
        Route::get('/index', 'Admin\LawyerRequestController@index')->name('index');
        Route::get('/show/{lawyer_request_id}', 'Admin\LawyerRequestController@show')->name('show');
    });
});

Route::get('lawyer-requests/generate/{id}', 'Admin\LawyerRequestController@generate')->name('lawyer-requests.generate');

Route::group(['prefix' => 'lawyer-requests', 'as' => 'frontend.lawyer-requests.'], function () {
    Route::get('create', [LawyerRequestController::class, 'create'])->name('create');
    Route::get('voucher/{id}', [LawyerRequestController::class, 'voucher'])->name('voucher');
    Route::get('index', [LawyerRequestController::class, 'index'])->name('index');
    Route::get('show/{id}', [LawyerRequestController::class, 'show'])->name('show');
    // Route::get('generate/{id}', [LawyerRequestController::class, 'generate'])->name('generate');
});
