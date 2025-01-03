<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ProkerController;
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

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    //clubs
    Route::resource('clubs', ClubController::class);

    //divisions
    Route::get('/clubs/{id_club}/divisions', [DivisionController::class, 'index'])->name('divisions.index');
    Route::get('/clubs/{id_club}/divisions/create', [DivisionController::class, 'create'])->name('divisions.create');
    Route::get('/divisions/{id}/edit', [DivisionController::class, 'edit'])->name('divisions.edit');
    Route::post('/clubs/{id_club}/divisions', [DivisionController::class, 'store'])->name('divisions.store');
    Route::put('/divisions/{id}', [DivisionController::class, 'update'])->name('divisions.update');
    Route::delete('/divisions/{id}', [DivisionController::class, 'destroy'])->name('divisions.destroy');

    Route::resource('prokers', ProkerController::class);

    Route::resource('anggotas', AnggotaController::class);
});
