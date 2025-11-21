<?php

use App\Http\Controllers\Admin\CsvAdminController;
use App\Http\Controllers\CsvUploadController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/csv', [CsvUploadController::class, 'index'])->name('csv.index');
    Route::get('/csv/create', [CsvUploadController::class, 'create'])->name('csv.create');
    Route::post('/csv', [CsvUploadController::class, 'store'])->name('csv.store');
    Route::delete('/csv/{csvUpload}', [CsvUploadController::class, 'destroy'])->name('csv.destroy');

    // Admin Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/csv', [CsvAdminController::class, 'index'])->name('csv.index');
        Route::get('/csv/{csvUpload}', [CsvAdminController::class, 'show'])->name('csv.show');
        Route::post('/csv/{csvUpload}/reprocess', [CsvAdminController::class, 'reprocess'])->name('csv.reprocess');
        Route::post('/csv/{csvUpload}/mark-completed', [CsvAdminController::class, 'markCompleted'])->name('csv.markCompleted');
        Route::delete('/csv/{csvUpload}', [CsvAdminController::class, 'destroy'])->name('csv.destroy');
        Route::get('/csv-data', [CsvAdminController::class, 'data'])->name('csv.data');
    });
});

require __DIR__.'/auth.php';
