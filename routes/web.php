<?php

use App\Http\Controllers\BulletinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ImportExportController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SchoolClassController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'app' => config('app.name', 'EduTrack Al Amal'),
        'environment' => app()->environment(),
        'view_compiled_path' => config('view.compiled'),
        'tmp_writable' => is_writable('/tmp'),
        'time' => now()->toIso8601String(),
    ]);
});

Route::get('/debug-config', function () {
    abort_unless(config('app.debug'), 404);

    return response()->json([
        'app_key_set' => filled(config('app.key')),
        'app_env' => config('app.env'),
        'app_debug' => config('app.debug'),
        'view_compiled' => config('view.compiled'),
        'cache_default' => config('cache.default'),
        'session_driver' => config('session.driver'),
        'db_connection' => config('database.default'),
        'tmp_writable' => is_writable('/tmp'),
    ]);
});

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/transformation-digitale', [PublicController::class, 'transformationDigitale'])->name('transformation-digitale');

Route::middleware(['auth'])->group(function () {
    Route::middleware('role:enseignant,encadrant')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('students', StudentController::class);
        Route::get('students/{student}/bulletin', [BulletinController::class, 'show'])->name('students.bulletin');
        Route::get('students/{student}/bulletin/pdf', [BulletinController::class, 'pdf'])->name('students.bulletin.pdf');

        Route::resource('classes', SchoolClassController::class);
        Route::resource('grades', GradeController::class);

        Route::resource('messages', MessageController::class)->except(['edit', 'update']);
        Route::get('messages/sent', [MessageController::class, 'sent'])->name('messages.sent');
        Route::patch('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('staff', StaffController::class);

        Route::prefix('import-export')->name('import-export.')->controller(ImportExportController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/students/import', 'importStudents')->name('students.import');
            Route::get('/students/export', 'exportStudents')->name('students.export');
            Route::get('/grades/export', 'exportGrades')->name('grades.export');
            Route::get('/statistics/export', 'exportStatistics')->name('statistics.export');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
