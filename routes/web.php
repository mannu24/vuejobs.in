<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\JobImportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check() ? redirect()->route('admin.blogs.index') : redirect()->route('admin.login'));

// Admin panel
Route::prefix('admin')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::middleware(['auth:web', 'admin'])->group(function () {
        Route::get('/', fn () => redirect()->route('admin.blogs.index'))->name('admin.dashboard');
        Route::resource('blogs', BlogController::class)->except(['show'])->names('admin.blogs');

        // Job import
        Route::get('jobs/import', [JobImportController::class, 'show'])->name('admin.jobs.import');
        Route::post('jobs/import', [JobImportController::class, 'import'])->name('admin.jobs.import.store');
        Route::get('jobs/import/template', [JobImportController::class, 'template'])->name('admin.jobs.import.template');

        // Utility routes (shared hosting helpers)
        Route::get('run/migrate', function () {
            Artisan::call('migrate', ['--force' => true]);
            return back()->with('success', 'Migrations executed: ' . Artisan::output());
        })->name('admin.run.migrate');

        Route::get('run/storage-link', function () {
            Artisan::call('storage:link');
            return back()->with('success', 'Storage link created: ' . Artisan::output());
        })->name('admin.run.storage-link');

        Route::get('run/optimize-clear', function () {
            Artisan::call('optimize:clear');
            return back()->with('success', 'Cache cleared: ' . Artisan::output());
        })->name('admin.run.optimize-clear');

        Route::get('run/seed-blogs', function () {
            Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\BlogPostsSeeder', '--force' => true]);
            return back()->with('success', 'Blog seeder executed: ' . Artisan::output());
        })->name('admin.run.seed-blogs');
    });
});
