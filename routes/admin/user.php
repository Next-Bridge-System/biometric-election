<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['permission:manage-users']], function () {
    Route::group(['prefix' => 'users'], function () {
        // Route::get('/', 'RegisterUserController@index')->name('users.index');
        Route::get('/{slug}', [UserController::class, 'index'])->name('users.index');
        Route::get('/edit/{id}', 'RegisterUserController@edit')->name('users.edit');
        Route::post('/update', 'RegisterUserController@update')->name('users.update');
        Route::post('/status', 'RegisterUserController@status')->name('users.status');
        Route::get('/audit/{id}', 'RegisterUserController@audit')->name('users.audit');
        // Route::get('/', 'Admin\GcUserController@index')->name('gc-users.index');
        Route::get('/verification/show/{user_id}', 'Admin\GcUserController@show')->name('gc-users.show');
        Route::get('/show/{id}', 'RegisterUserController@show')->name('users.show');
    });
});
