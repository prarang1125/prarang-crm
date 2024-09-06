<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Auth\Events\Login;
use App\Http\Controllers\AccountsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\AdminController;


Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'accounts'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('login', [LoginController::class, 'index'])->name('accounts.login');
        Route::post('authenticate', [LoginController::class, 'authenticate'])->name('accounts.authenticate');
    });

    Route::group(['middleware' => 'auth'], function(){
        Route::get('logout', [LoginController::class, 'logout'])->name('accounts.logout');
        Route::get('dashboard', [AccountsController::class, 'index'])->name('accounts.dashboard');
    });
});

Route::group(['prefix' => 'admin'], function(){
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
        // Route::post('users-store', [AdminController::class, 'userStore'])->name('admin.users-store');
    });

    Route::group(['middleware' => 'admin.auth'], function(){
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
        Route::get('user-profile', [AdminController::class, 'userProfile'])->name('admin.user-profile');
        // Route::post('update-profile', [AdminController::class, 'updateProfile'])->name('admin.update-profile');
        Route::get('user-listing', [AdminController::class, 'userListing'])->name('admin.user-listing');
        Route::get('user-register', [AdminController::class, 'useruRegister'])->name('admin.user-register');
        Route::post('users-store', [AdminController::class, 'userStore'])->name('admin.users-store');
        Route::post('users-delete/{id}', [AdminController::class, 'userDelete'])->name('admin.users-delete');
        Route::get('user-edit/{id}', [AdminController::class, 'userEdit'])->name('admin.user-edit');
        Route::put('user-update/{id}', [AdminController::class, 'userUpdate'])->name('admin.user-update');
    });
});






