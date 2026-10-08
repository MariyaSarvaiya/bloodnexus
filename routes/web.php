<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BloodController;
use App\Http\Controllers\BloodRequestController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\AiAssistantController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    if (session('fixed_admin') === true) {
        return redirect()->route('admin.dashboard');
    }
    if (auth()->check() && auth()->user()->role === 'donor') {
        return view('donor-home');
    }
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/admin/login', fn () => view('admin-login'))->name('admin.login');

    Route::get('/account-review', [App\Http\Controllers\AccountAppealController::class, 'create'])->name('account.review');
    Route::post('/account-review', [App\Http\Controllers\AccountAppealController::class, 'store'])->name('account.review.store');
    Route::get('/account-review/track', [App\Http\Controllers\AccountAppealController::class, 'track'])->name('account.review.track');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/register/user', [AuthController::class, 'showUserRegister'])->name('register.user');
    Route::post('/register/user', [AuthController::class, 'registerUser'])->name('register.user.submit');
    Route::get('/register/donor', [AuthController::class, 'showDonorRegister'])->name('register.donor');
    Route::post('/register/donor', [AuthController::class, 'registerDonor'])->name('register.donor.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['admin', 'security.activity'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/requests', [AdminController::class, 'requests'])->name('requests');
    Route::get('/donors', [AdminController::class, 'donors'])->name('donors');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/donations', [AdminController::class, 'donations'])->name('donations');
    Route::get('/donations/pdf', [AdminController::class, 'downloadDonationReport'])->name('donations.pdf');
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/pdf', [AdminController::class, 'downloadSystemReport'])->name('reports.pdf');
    Route::get('/reports/pdf/view', [AdminController::class, 'viewSystemReport'])->name('reports.pdf.view');
    Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/demand-report', [AdminController::class, 'demandReport'])->name('demand.report');
    Route::get('/demand-report/pdf', [AdminController::class, 'downloadDemandReport'])->name('demand.report.pdf');
    Route::get('/demand-report/pdf/view', [AdminController::class, 'viewDemandReport'])->name('demand.report.pdf.view');
    Route::get('/critical', [AdminController::class, 'critical'])->name('critical');
    Route::get('/notifications', [AdminController::class, 'notifications'])->name('notifications');
    Route::get('/security', [AdminController::class, 'security'])->name('security');
    Route::get('/security/appeals', [AdminController::class, 'appeals'])->name('security.appeals');
    Route::post('/security/appeals/{appeal}/review', [AdminController::class, 'reviewAppeal'])->name('security.appeals.review');
    Route::get('/donor-intelligence', [AdminController::class, 'donorIntelligence'])->name('donor.intelligence');
    Route::get('/medical-history', [AdminController::class, 'medicalHistory'])->name('medical.history');
    Route::get('/messaging', [AdminController::class, 'messagingControl'])->name('messaging');
    Route::get('/ai', [AdminController::class, 'adminAi'])->name('ai');
    Route::post('/ai/ask', [AdminController::class, 'adminAiAsk'])->name('ai.ask');
    Route::post('/security/user/{user}/unlock', [AdminController::class, 'unlockUser'])->name('security.user.unlock');
    Route::post('/security/user/{user}/warning', [AdminController::class, 'sendSecurityWarning'])->name('security.user.warning');
    Route::post('/security/user/{user}/block', [AdminController::class, 'blockUserForSecurity'])->name('security.user.block');
    Route::post('/security/{securityLog}/resolve', [AdminController::class, 'resolveSecurityLog'])->name('security.resolve');
    Route::post('/user/{user}/suspend', [AdminController::class, 'suspendUser'])->name('user.suspend');
    Route::post('/user/{user}/unsuspend', [AdminController::class, 'unsuspendUser'])->name('user.unsuspend');
    Route::post('/blood-request/{bloodRequest}/assign-donor', [AdminController::class, 'assignDonor'])->name('request.assign');
    Route::post('/blood-request/{bloodRequest}/status', [AdminController::class, 'updateRequestStatus'])->name('request.status');
    Route::delete('/blood-request/{bloodRequest}', [AdminController::class, 'deleteRequest'])->name('request.delete');
    Route::delete('/user/{user}', [AdminController::class, 'deleteUser'])->name('user.delete');
    Route::post('/donor/{donor}/toggle', [AdminController::class, 'toggleDonor'])->name('donor.toggle');
    Route::delete('/donor/{donor}', [AdminController::class, 'deleteDonor'])->name('donor.delete');
});

Route::middleware(['auth', 'security.activity'])->group(function () {
    Route::get('/donor/certificate/{bloodRequest}', [AdminController::class, 'donorCertificateView'])->whereNumber('bloodRequest')->name('donor.certificate');
    Route::get('/donor/certificate/{bloodRequest}/download', [AdminController::class, 'donorCertificate'])->whereNumber('bloodRequest')->name('donor.certificate.download');
    Route::get('/dashboard', function () {
        if (session('fixed_admin') === true) {
            return redirect()->route('admin.dashboard');
        }
        $user = auth()->user();
        if ($user->role === 'admin') return redirect()->route('admin.dashboard');
        if ($user->role === 'donor') return redirect()->route('donor.dashboard');
        return app(DashboardController::class)->index();
    })->name('dashboard');

    Route::get('/donor/{donor}', [DonorController::class, 'showProfile'])->whereNumber('donor')->name('donor.profile');

    Route::post('/blood-search/{donor}/request', [BloodRequestController::class, 'sendToDonor'])
        ->whereNumber('donor')
        ->name('blood.search.request');

    Route::get('/blood-search', function (Request $request) {
        abort_unless(auth()->user()->role === 'user', 403, 'Only Blood Need users can search for blood.');
        return app(BloodController::class)->search($request);
    })->name('blood.search');

    Route::get('/blood-request', [BloodRequestController::class, 'create'])->name('blood.request');
    Route::post('/blood-request', [BloodRequestController::class, 'store'])->name('blood.request.store');
    Route::get('/my-requests', [BloodRequestController::class, 'myRequests'])->name('blood.requests.mine');
    Route::delete('/my-requests/{bloodRequest}/cancel', [BloodRequestController::class, 'cancel'])->name('blood.requests.cancel');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    Route::get('/ai-assistant', [AiAssistantController::class, 'index'])->name('ai.assistant');
    Route::post('/ai-assistant/ask', [AiAssistantController::class, 'ask'])->name('ai.assistant.ask');

    Route::prefix('donor')->name('donor.')->group(function () {
        Route::get('/dashboard', [DonorController::class, 'dashboard'])->name('dashboard');
        Route::get('/requests', [DonorController::class, 'requests'])->name('requests');
        Route::post('/requests/{bloodRequest}/accept', [DonorController::class, 'accept'])->name('request.accept');
        Route::post('/requests/{bloodRequest}/reject', [DonorController::class, 'reject'])->name('request.reject');
        Route::post('/requests/{bloodRequest}/complete', [DonorController::class, 'complete'])->name('request.complete');
        Route::post('/availability', [DonorController::class, 'toggleAvailability'])->name('availability');
        Route::post('/medical-history', [DonorController::class, 'updateMedicalHistory'])->name('medical-history');
    });
});
