<?php

use App\Http\Controllers\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\TeamController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
    
    Route::middleware(['auth', 'role:super_admin'])->group(function () {
        Route::get(
            '/superadmin/invite-client',
            [InvitationController::class, 'createClient']
        )->name('superadmin.invite-client');

        Route::post(
            '/superadmin/invite-client',
            [InvitationController::class, 'inviteClient']
        )->name('superadmin.invite-client.store');
    });

    Route::middleware(['auth', 'role:admin'])
    ->get('/admin/dashboard', [AdminDashboardController::class, 'index'])
    ->name('admin.dashboard');

    Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/invite-team', [InvitationController::class, 'createTeam'])
            ->name('invite-team');

        Route::post('/invite-team', [InvitationController::class, 'inviteTeam'])
            ->name('invite-team.store');
    });

    Route::middleware(['auth', 'role:admin,member'])->group(function () {
        Route::get('/members', [TeamController::class, 'index'])
        ->name('admin.team.index');

        Route::get('/create-urls', [ShortUrlController::class, 'create'])
            ->name('create-urls');

        Route::post('/urls', [ShortUrlController::class, 'store'])
            ->name('urls.store');
    });
    
    Route::middleware(['auth', 'role:member'])->group(function () {
        Route::get('/member/dashboard', [DashboardController::class, 'member'])
            ->name('member.dashboard');
    });

    Route::middleware(['auth', 'role:super_admin'])
    ->prefix('super-admin')
    ->group(function () {
        Route::get('/companies', [CompanyController::class, 'index'])
            ->name('companies.index');

        Route::post('/companies', [CompanyController::class, 'store'])
            ->name('companies.store');
    });
    Route::post('/invitations', [InvitationController::class, 'store'])
    ->name('invitations.store');
    Route::get('/invitations/{token}', [InvitationController::class, 'showAccept'])
    ->name('invitations.accept');

    Route::post('/invitations/{token}', [InvitationController::class, 'accept'])
    ->name('invitations.accept.submit');
    Route::middleware(['auth', 'role:super_admin'])
    ->get('/superadmin/dashboard', [SuperAdminDashboardController::class, 'index'])
    ->name('superadmin.dashboard');
});

Route::get('/s/{shortCode}', [ShortUrlController::class, 'redirect'])
->name('urls.redirect');
Route::get('/', function () {
    return view('welcome');
});
