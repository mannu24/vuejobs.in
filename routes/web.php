<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\JobImportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin panel
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:web', 'admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.blogs.index'));
        Route::resource('blogs', BlogController::class)->except(['show'])->names('admin.blogs');

        // Job import
        Route::get('jobs/import', [JobImportController::class, 'show'])->name('admin.jobs.import');
        Route::post('jobs/import', [JobImportController::class, 'import'])->name('admin.jobs.import.store');
        Route::get('jobs/import/template', [JobImportController::class, 'template'])->name('admin.jobs.import.template');
    });
});
