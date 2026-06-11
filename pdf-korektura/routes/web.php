<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PdfDocumentController;
use App\Http\Controllers\AdminController;
use App\Livewire\Dashboard;
use App\Livewire\PdfUpload;
use App\Livewire\PdfPool;
use App\Livewire\MyAssignments;
use App\Livewire\PdfDetail;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Admin\AuditLog;
use App\Livewire\Admin\TitleManagement;
use App\Livewire\Admin\Archive;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware(['role:Editor|Grafik|Admin'])->group(function () {
        Route::get('/pdf/upload', PdfUpload::class)->name('pdf.upload');
        Route::get('/pdf/{pdfDocument}', PdfDetail::class)->name('pdf.detail');
    });

    Route::middleware(['role:Korektor|Admin'])->group(function () {
        Route::get('/pool', PdfPool::class)->name('pdf.pool');
        Route::get('/my-assignments', MyAssignments::class)->name('pdf.assignments');
    });

    Route::get('/pdf/{pdfDocument}/download/{version?}', [PdfDocumentController::class, 'download'])
        ->name('pdf.download');
    Route::get('/pdf/{pdfDocument}/preview', [PdfDocumentController::class, 'preview'])
        ->name('pdf.preview');
    Route::get('/pdf/{pdfDocument}/download-silent/{version?}', [PdfDocumentController::class, 'downloadSilent'])
        ->name('pdf.download.silent');
    Route::get('/pdf/{pdfDocument}/preview-silent', [PdfDocumentController::class, 'previewSilent'])
        ->name('pdf.preview.silent');

    Route::middleware(['role:Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UserManagement::class)->name('users');
        Route::get('/audit-log', AuditLog::class)->name('audit-log');
        Route::get('/titles', TitleManagement::class)->name('titles');
        Route::get('/archive', Archive::class)->name('archive');
        Route::post('/pdf/{pdfDocument}/release', [AdminController::class, 'releasePdf'])
            ->name('pdf.release');
        Route::post('/pdf/{pdfDocument}/reassign', [AdminController::class, 'reassignPdf'])
            ->name('pdf.reassign');
    });
});
