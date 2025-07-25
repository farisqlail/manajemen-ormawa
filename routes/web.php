<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProkerController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });

Route::get('/', [AuthController::class, 'loginForm'])->name('login');

Route::get('/ormawa/{id}', [ClubController::class, 'showProfile'])->name('ormawa.profile');
Route::get('/ormawa/{id}/prokers', [ClubController::class, 'showProkers'])->name('ormawa.prokers');
Route::get('/ormawa/{id}/activities', [ClubController::class, 'showActivities'])->name('ormawa.activities');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard')->middleware('auth');

    //clubs
    Route::resource('clubs', ClubController::class)->parameters([
        'clubs' => 'ormawa'
    ]);
    Route::get('/clubs/{ormawa}/editOramawa', [ClubController::class, 'editOrmawa'])->name('clubs.editOrmawa');
    Route::put('/clubs/ormawa/{ormawa}', [ClubController::class, 'updateOrmawa'])->name('clubs.updateOrmawa');

    //divisions
    Route::get('/clubs/{id_club}/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    Route::get('/clubs/{id_club}/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::get('/divisions/{id}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
    Route::post('/clubs/{id_club}/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::put('/divisions/{id}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{id}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

    //prokers
    Route::resource('prokers', ProkerController::class);

    //anggota
    Route::resource('anggotas', AnggotaController::class);
    Route::get('/get-divisi-by-club/{id}', [AnggotaController::class, 'getByClub']);

    //proker ormawa
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/prokers/{id}/export', [ProkerController::class, 'exportProposalToWord'])->name('prokers.export');
    Route::get('/prokers/{id}/export/laporan', [ProkerController::class, 'exportLaporanToWord'])->name('prokers.exportLaporan');
    Route::post('/prokers/upload-pdf/{id}', [ProkerController::class, 'uploadPdf'])->name('prokers.uploadPdf');

    //profile user
    Route::get('/profile/user', [UserProfileController::class, 'show'])->name('profile.user.show')->middleware('auth');
    Route::get('/profile/edit/{id}', [UserProfileController::class, 'edit'])->name('profile.user.edit')->middleware('auth');
    Route::put('/profile/update/{id}', [UserProfileController::class, 'update'])->name('profile.user.update')->middleware('auth');

    //activity
    Route::resource('activities', ActivityController::class);

    //user
    Route::resource('users', AuthController::class)->except(['loginForm', 'login', 'registerForm', 'register', 'logout']);
    Route::post('/users/{id}/approve', [AuthController::class, 'approveUser'])->name('users.approve');
    Route::delete('/users/{id}', [AuthController::class, 'reject'])->name('users.reject');

    //dasboard
    Route::get('/prokers/club/{clubId}', [DashboardController::class, 'showClubProkers'])->name('prokers.club');
    Route::post('/prokers/{id}/approve', [ProkerController::class, 'approveProker'])->name('prokers.approve');
    Route::put('/prokers/{id}/reject', [ProkerController::class, 'rejectProker'])->name('prokers.reject');
    Route::post('/prokers/{id}/approve/laporan', [ProkerController::class, 'approveProkerLaporan'])->name('prokers.approve.laporan');
    Route::delete('/prokers/{id}/reject/laporan', [ProkerController::class, 'rejectProkerLaporan'])->name('prokers.reject.laporan');
    Route::post('/prokers/{id}/reject/laporan', [ProkerController::class, 'rejectProkerNoReason'])->name('prokers.rejectNoReason.laporan');
    Route::post('/prokers/{id}/reject/proposal', [ProkerController::class, 'rejectProposalNoReason'])->name('prokers.rejectNoReason.proposal');
    Route::get('/prokers/{id}/download', [ProkerController::class, 'downloadLPJ'])->name('prokers.download');

    Route::post('/reset-notification', [NotificationController::class, 'resetNotification']);
});
