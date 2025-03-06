<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;

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
    return view('welcome');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Client Registration Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Manager Registration Routes
Route::get('/register/manager', [AuthController::class, 'showManagerRegistrationForm'])->name('register.manager');
Route::post('/register/manager', [AuthController::class, 'registerManager']);

// Courier Registration Routes
Route::get('/register/courier', [AuthController::class, 'showCourierRegistrationForm'])->name('register.courier');
Route::post('/register/courier', [AuthController::class, 'registerCourier']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
