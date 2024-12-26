<?php

use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('admin.auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'postLogin'])->name('login.post');
    Route::get('/register', [AuthController::class, 'registration'])->name('registration');
    Route::post('/register-user', [AuthController::class, 'registrationStore'])->name('registration.post');

    Route::get('forget-password', [ForgotPasswordController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password-post', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('forget.password.post');

    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('index');
    })->name('dashboard.index');
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('changePassword');
    Route::post('/change-password-user', [AuthController::class, 'changePassword'])->name('changePassword.post');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::post('update-profile', [AuthController::class, 'updateProfile'])->name('update.profile');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::resource('/users',UserController::class);

    Route::get('/roles', [RolePermissionController::class, 'index'])->name('roles.index');
    Route::resource('roles',RolePermissionController::class);
});
