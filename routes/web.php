<?php

use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\ClientApprovalController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

// Installer routes (accessible before installation)
Route::get('/installer', function () {
    return redirect()->route('installer.welcome');
});

Route::prefix('installer')->name('installer.')->group(function () {
    Route::get('/welcome', [InstallerController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallerController::class, 'requirements'])->name('requirements');
    Route::get('/database', [InstallerController::class, 'database'])->name('database');
    Route::post('/database', [InstallerController::class, 'databaseStore'])->name('database.store');
    Route::get('/migrate', [InstallerController::class, 'migrate'])->name('migrate');
    Route::post('/migrate/process', [InstallerController::class, 'migrateProcess'])->name('migrate.process');
    Route::get('/admin', [InstallerController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallerController::class, 'adminStore'])->name('admin.store');
    Route::get('/complete', [InstallerController::class, 'complete'])->name('complete');
});

// Public routes
Route::get('/', function () {
    // Redirect to installer if not installed
    if (!file_exists(storage_path('installed'))) {
        return redirect()->route('installer.welcome');
    }
    return redirect()->route('login');
});

// Fallback redirect for /install to installer welcome
Route::get('/install', function () {
    return redirect()->route('installer.welcome');
});

// Client approval routes (no authentication required)
Route::prefix('approval')->name('approval.')->group(function () {
    Route::get('/{token}', [ClientApprovalController::class, 'review'])
        ->middleware('token.valid')
        ->name('review');
    
    Route::post('/{token}/approve', [ClientApprovalController::class, 'approve'])
        ->middleware('token.valid')
        ->name('approve');
    
    Route::post('/{token}/reject', [ClientApprovalController::class, 'reject'])
        ->middleware('token.valid')
        ->name('reject');
    
    Route::get('/status/expired', [ClientApprovalController::class, 'expired'])
        ->name('expired');
    
    Route::get('/status/already-responded', [ClientApprovalController::class, 'alreadyResponded'])
        ->name('already-responded');
    
    Route::get('/status/success', [ClientApprovalController::class, 'success'])
        ->name('success');
});

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profile management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/avatar', [ProfileController::class, 'deleteAvatar'])->name('avatar.delete');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Approval management
    Route::middleware(['permission:approvals.view'])->group(function () {
        Route::resource('approvals', ApprovalController::class);
        
        Route::prefix('approvals/{approval}')->name('approvals.')->group(function () {
            Route::post('/attachments', [ApprovalController::class, 'addAttachment'])
                ->name('attachments.store');
            
            Route::delete('/attachments/{attachment}', [ApprovalController::class, 'deleteAttachment'])
                ->name('attachments.destroy');
            
            Route::get('/attachments/{attachment}/download', [ApprovalController::class, 'downloadAttachment'])
                ->name('attachments.download');
            
            Route::post('/send-reminder', [ApprovalController::class, 'sendReminder'])
                ->name('send-reminder');
            
            Route::get('/regenerate-token', [ApprovalController::class, 'regenerateToken'])
                ->name('regenerate-token');
            
            Route::get('/pdf', [ApprovalController::class, 'downloadPdf'])
                ->name('pdf');
            
            Route::get('/history', [ApprovalController::class, 'history'])
                ->name('history');
        });
    });

    // Client management
    Route::middleware(['permission:clients.view'])->group(function () {
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])
            ->name('clients.toggle-status');
        Route::get('clients-search', [ClientController::class, 'search'])
            ->name('clients.search');
    });

    // User management
    Route::middleware(['permission:users.view'])->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');
    });

    // Role & Permission management
    Route::middleware(['permission:roles.view'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::get('permissions', [RoleController::class, 'permissions'])
            ->name('permissions.index');
    });

    // Audit Logs (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])
            ->name('audit-logs.index');
        Route::get('audit-logs/{auditLog}', [App\Http\Controllers\AuditLogController::class, 'show'])
            ->name('audit-logs.show');
    });

    // Email Templates (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('email-templates', App\Http\Controllers\EmailTemplateController::class);
    });
});

// Auth routes
require __DIR__.'/auth.php';
