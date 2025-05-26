<?php

use App\Http\Controllers\Admin\ComplaintController as AdminComplaintController;
use App\Http\Controllers\Frontend\ComplaintController;
use Illuminate\Support\Facades\Route;

// ADMIN
Route::group(['middleware' => ['admin']], function () {
    Route::group(['prefix' => 'admin/complaint', 'as' => 'complaint.', 'middleware' => ['permission:lawyer_complaint']], function () {
        Route::get('index', [AdminComplaintController::class, 'index'])->name('index');
        Route::get('detail/{id}', [AdminComplaintController::class, 'show'])->name('show');
    });
});

// FRONTEND
Route::get('complaint/create', [ComplaintController::class, 'create'])->name('frontend.complaint');
Route::get('complaint/voucher/{payment_id}', [ComplaintController::class, 'voucher'])->name('frontend.complaint-voucher');
Route::get('complaint/list', [ComplaintController::class, 'index'])->name('frontend.complaint');
