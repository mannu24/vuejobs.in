<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\JobAlertController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// Auth (guest)
Route::middleware('guest')->group(function (): void {
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/login', [AuthController::class, 'login']);
});

// Google OAuth (no guard needed)
Route::get('auth/google/redirect', [AuthController::class, 'googleRedirect']);
Route::post('auth/google/callback', [AuthController::class, 'googleCallback']);

// Authenticated
Route::middleware('auth:api')->group(function (): void {
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Profile
    Route::get('me', [ProfileController::class, 'show']);
    Route::put('me', [ProfileController::class, 'update']);

    // Companies
    Route::apiResource('companies', CompanyController::class);

    // Jobs (employer)
    Route::apiResource('jobs', JobController::class)->only(['store', 'update', 'destroy']);
    Route::get('jobs/mine', [JobController::class, 'index']);
    Route::get('jobs/mine/{job}', [JobController::class, 'showPrivate']);
    Route::post('jobs/{job}/publish', [JobController::class, 'publish']);
    Route::post('jobs/{job}/feature', [JobController::class, 'feature']);
    Route::post('jobs/{job}/archive', [JobController::class, 'archive']);

    // Applications
    Route::post('jobs/{job}/apply', [ApplicationController::class, 'store']);
    Route::get('jobs/{job}/applications', [ApplicationController::class, 'index']);
    Route::get('my-applications', [ApplicationController::class, 'myApplications']);
    Route::patch('applications/{application}', [ApplicationController::class, 'update']);

    // Saved jobs
    Route::post('jobs/{job}/save', [JobController::class, 'saveJob']);
    Route::delete('jobs/{job}/save', [JobController::class, 'unsaveJob']);
    Route::get('saved-jobs', [JobController::class, 'savedJobs']);

    // Job alerts
    Route::get('job-alerts', [JobAlertController::class, 'index']);
    Route::post('job-alerts', [JobAlertController::class, 'store']);
    Route::delete('job-alerts/{alert}', [JobAlertController::class, 'destroy']);

    // Messages
    Route::get('messages', [MessageController::class, 'index']);
    Route::post('messages', [MessageController::class, 'store']);
});

// Public
Route::get('jobs', [JobController::class, 'publicIndex']);
Route::get('jobs/{job:slug}', [JobController::class, 'showPublic']);
Route::get('companies/{company:slug}', [CompanyController::class, 'showPublic']);

// Sitemap
Route::get('sitemap.xml', [\App\Http\Controllers\Api\SitemapController::class, 'index']);
