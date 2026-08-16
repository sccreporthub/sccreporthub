<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SCC ReportHub Web Routes
|--------------------------------------------------------------------------
*/

// ─── Landing Page ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match (true) {
            $user->isAdmin()       => redirect()->route('admin.dashboard'),
            $user->isMaintenance() => redirect()->route('maintenance.dashboard'),
            default                => redirect()->route('faculty.dashboard'),
        };
    }
    return view('landing');
})->name('landing');

// ─── Authentication Routes ────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->middleware('throttle:10,1')->name('register.post');
    Route::get('/forgot-password',  [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('password.email');
    Route::post('/resend-reset',    [AuthController::class, 'resendResetLink'])->middleware('throttle:3,1')->name('password.resend');
    Route::get('/reset-password/{token}',  [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password',  [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.change.update');

    // ─── Profile ──────────────────────────────────────────────────────────────
    Route::get('/profile',  [UserManagementController::class, 'profile'])->name('profile.edit');
    Route::post('/profile', [UserManagementController::class, 'updateProfile'])->name('profile.update');
    Route::get('/settings', fn() => view('settings.index'))->name('settings.index');

    // ─── Notifications ────────────────────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',             [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('unread-count');
        Route::get('/recent',       [NotificationController::class, 'recent'])->name('recent');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('mark-all-read');
        Route::post('/{notification}/read', [NotificationController::class, 'markRead'])->name('mark-read');
    });
});

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');

    // Ticket Management
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/',                          [AdminController::class, 'tickets'])->name('index');
        Route::get('/{ticket}',                  [AdminController::class, 'showTicket'])->name('show');
        Route::post('/{ticket}/approve',         [AdminController::class, 'approveTicket'])->name('approve');
        Route::post('/{ticket}/reject',          [AdminController::class, 'rejectTicket'])->name('reject');
        Route::post('/{ticket}/assign',          [AdminController::class, 'assignTicket'])->name('assign');
        Route::post('/{ticket}/priority',        [AdminController::class, 'updatePriority'])->name('priority');
        Route::post('/{ticket}/verify',          [AdminController::class, 'verifyCompletion'])->name('verify');
        Route::get('/{ticket}/receipt',          [AdminController::class, 'generateReceipt'])->name('receipt');
    });

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',                          [UserManagementController::class, 'index'])->name('index');
        Route::get('/create',                    [UserManagementController::class, 'create'])->name('create');
        Route::post('/',                         [UserManagementController::class, 'store'])->name('store');
        Route::get('/{user}/edit',               [UserManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}',                    [UserManagementController::class, 'update'])->name('update');
        Route::post('/{user}/toggle-status',     [UserManagementController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/{user}',                 [UserManagementController::class, 'destroy'])->name('destroy');
    });

    // Facilities
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/',                          [AdminController::class, 'facilities'])->name('index');
        Route::post('/',                         [AdminController::class, 'storeFacility'])->name('store');
        Route::put('/{facility}',                [AdminController::class, 'updateFacility'])->name('update');
        Route::delete('/{facility}',             [AdminController::class, 'destroyFacility'])->name('destroy');
    });

    // History
    Route::get('/history',  [AdminController::class, 'historyLogs'])->name('history');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

    // Feedback
    Route::get('/feedback', [FeedbackController::class, 'adminIndex'])->name('feedback');

    // Maintenance Monitoring
    Route::prefix('monitoring')->name('monitoring.')->group(function () {
        Route::get('/',                 [AdminController::class, 'monitoringIndex'])->name('index');
        Route::get('/{ticket}',         [AdminController::class, 'monitoringShow'])->name('show');
        Route::post('/{ticket}/verify', [AdminController::class, 'verifyCompletion'])->name('verify');
        Route::get('/{ticket}/receipt', [AdminController::class, 'generateReceipt'])->name('receipt');
    });
});

// ─── Faculty / Staff Routes ───────────────────────────────────────────────────
Route::middleware(['auth', 'faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'facultyDashboard'])->name('dashboard');

    // Tickets
    Route::prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/',         [TicketController::class, 'index'])->name('index');
        Route::get('/create',   [TicketController::class, 'create'])->name('create');
        Route::post('/',        [TicketController::class, 'store'])->name('store');
        Route::get('/track',    [TicketController::class, 'track'])->name('track');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
        Route::get('/{ticket}/job-request', [TicketController::class, 'jobRequestForm'])->name('job-request');
    });

    Route::get('/history', [TicketController::class, 'history'])->name('history');

    // Feedback
    Route::prefix('feedback')->name('feedback.')->group(function () {
        Route::get('/{ticket}/create',  [FeedbackController::class, 'create'])->name('create');
        Route::post('/{ticket}',        [FeedbackController::class, 'store'])->name('store');
    });
});

// ─── Maintenance Staff Routes ─────────────────────────────────────────────────
Route::middleware(['auth', 'maintenance'])->prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'maintenanceDashboard'])->name('dashboard');

    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/',                          [MaintenanceController::class, 'assignedTasks'])->name('index');
        Route::get('/completed',                 [MaintenanceController::class, 'completedTasks'])->name('completed');
        Route::get('/{ticket}',                  [MaintenanceController::class, 'showTask'])->name('show');
        Route::post('/{ticket}/start',           [MaintenanceController::class, 'startTask'])->name('start');
        Route::post('/{ticket}/update-repair',   [MaintenanceController::class, 'updateRepair'])->name('update-repair');
        Route::post('/{ticket}/resolve',         [MaintenanceController::class, 'resolveTask'])->name('resolve');
    });
});
