<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\RestaurantController;
use App\Models\Company;
use App\Models\Product;

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

Route::get('/', [HomeController::class, 'index'])->name('home');

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
    // Dashboard
    Route::get('/dashboard', function () {
        $featuredRestaurants = Company::with('address')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(4)
            ->get();

        $featuredProducts = Product::with('company')
            ->where('is_featured', true)
            ->where('is_available', true)
            ->take(4)
            ->get();

        return view('dashboard', compact('featuredRestaurants', 'featuredProducts'));
    })->name('dashboard');

    // Restaurant Routes
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/{uuid}', [RestaurantController::class, 'show'])->name('restaurants.show');

    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addItem'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'updateItem'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{uuid}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{uuid}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Manager Order Management Routes
    Route::middleware(['auth', 'role:manager'])->group(function () {
        Route::get('/order-management', [OrderController::class, 'manageOrders'])->name('orders.manage');
        Route::post('/order-management/{uuid}/approve', [OrderController::class, 'approveOrder'])->name('orders.approve');
        Route::post('/order-management/{uuid}/reject', [OrderController::class, 'rejectOrder'])->name('orders.reject');
        Route::post('/order-management/{uuid}/assign-courier', [OrderController::class, 'assignCourier'])->name('orders.assign-courier');
        Route::post('/order-management/{uuid}/ready', [OrderController::class, 'markReady'])->name('orders.ready');
    });

    // Courier Order Management Routes
    Route::middleware(['auth', 'role:courier'])->group(function () {
        Route::get('/deliveries', [OrderController::class, 'courierOrders'])->name('orders.courier');
        Route::post('/orders/{uuid}/on-the-way', [OrderController::class, 'markOnTheWay'])->name('orders.on-the-way');
        Route::post('/orders/{uuid}/delivered', [OrderController::class, 'markDelivered'])->name('orders.delivered');
    });
});
