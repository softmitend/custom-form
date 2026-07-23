<?php

use App\Http\Controllers\Admin\WebsiteProjectRequestController as AdminWebsiteProjectRequestController;
use App\Http\Controllers\Auth\AdminSessionController;
use App\Http\Controllers\WebsiteProjectRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/formulir', function () {
    return view('formulir');
})->name('website-project-requests.create');

Route::post('/formulir', [WebsiteProjectRequestController::class, 'store'])
    ->name('website-project-requests.store');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('guest')->group(function (): void {
        Route::get('/login', [AdminSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminSessionController::class, 'store'])->name('login.store');
    });

    Route::post('/logout', [AdminSessionController::class, 'destroy'])->name('logout');
});

Route::prefix('admin')->name('admin.')->middleware('admin.auth')->group(function (): void {
    Route::get('/', [AdminWebsiteProjectRequestController::class, 'index'])
        ->name('website-project-requests.index');

    Route::get('/responses', [AdminWebsiteProjectRequestController::class, 'index'])
        ->name('website-project-requests.responses');

    Route::get('/responses/{websiteProjectRequest}', [AdminWebsiteProjectRequestController::class, 'show'])
        ->name('website-project-requests.show');

    Route::patch('/responses/{websiteProjectRequest}/status', [AdminWebsiteProjectRequestController::class, 'updateStatus'])
        ->name('website-project-requests.update-status');
});
