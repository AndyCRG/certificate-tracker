<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::get('/participants', [CertificateController::class, 'participants_dashboard'])->name('participants.index');

Route::get('/dashboard', [CertificateController::class, 'dashboard'])->name('dashboard');



// Certificates (resource)
Route::resource('certificates', CertificateController::class);

// ---------------------------
// PARTICIPANT ROUTES (FIXED)
// ---------------------------

// 1. List all participants
Route::get('/participants', [CertificateController::class, 'participantsIndex'])
    ->name('participants.index');

// 2. Create participant form
Route::get('/participants/create', [CertificateController::class, 'singleparticipant'])
    ->name('participants.create');

// 3. Store single participant
Route::post('/participants/store', [CertificateController::class, 'store2'])
    ->name('participants.store');

// 4. Upload Excel/CSV form
Route::get('/participants/upload', [CertificateController::class, 'uploadForm'])
    ->name('participants.uploadForm');

// 5. Upload Excel/CSV handler
Route::post('/participants/upload', [CertificateController::class, 'upload'])
    ->name('participants.upload');

// 6. MUST BE LAST → show participant details
Route::get('/participants/{id}', [CertificateController::class, 'participantsShow'])
    ->name('participants.show');


// Toggle collected
Route::post(
    '/certificates/{id}/toggle-collected',
    [CertificateController::class, 'toggleCollected']
)->name('certificates.toggleCollected');

// // Update status
// Route::post(
//     '/certificate/update-status/{id}',
//     [CertificateController::class, 'updateStatus']
// )->name('certificate.updateStatus');


// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/participants/undo-last-upload', [CertificateController::class, 'undoLastUpload'])
    ->name('participants.undoLastUpload');

Route::post('/participants/undo', [CertificateController::class, 'undoUpload'])
    ->name('participants.undo');

// Route to show participants for a specific course
Route::get('/certificates/{courseId}/participants', [CertificateController::class, 'showParticipants'])
    ->name('certificates.participants');


require __DIR__ . '/auth.php';
