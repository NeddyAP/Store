<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\OrderController;
use App\Http\Controllers\Front\ShopController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.detail');
Route::post('/shop/{id}/add', [CartController::class, 'add'])->name('cart.add');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/cart/{id}/delete', [CartController::class, 'delete'])->name('cart.delete');

// Proccess
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

// Order
Route::post('/order/{id}/create', [OrderController::class, 'create'])->name('order.create');

Route::get('/contact', function () {
    return view('front.contact.index');
});

Route::get('/about', function () {
    return view('front.about.index');
});

Route::get('/thankyou', function () {
    return view('front.cart.thankyou');
});

Auth::routes(['register' => true]);
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('user.register');
Route::post('/register', [RegisterController::class, 'register'])->name('user.register.submit');
Route::get('/logout', [LoginController::class, 'userLogout'])->name('user.logout');

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
});

// Admin Protected Routes
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Products
    Route::get('products/search', [ProductController::class, 'search'])->name('products.search');
    Route::post('products/{id}/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::resource('products', ProductController::class);

    // User
    Route::get('user/', [UserController::class, 'index'])->name('user.index');
    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::get('user/{id}', [UserController::class, 'detail'])->name('user.detail');
    Route::get('user/{id}/search', [UserController::class, 'shippingSearch'])->name('user.shipping.search');
    // Route::get('user/search', 'Admin\ProductController@search')->name('user.search');
    // Route::post('user/{id}/trash', 'Admin\ProductController@trash')->name('user.trash');

    // Shipping
    Route::get('shipping/', [ShippingController::class, 'index'])->name('shipping.index');
    Route::get('shipping/search', [ShippingController::class, 'search'])->name('shipping.search');
    Route::get('shipping/{id}', [ShippingController::class, 'detail'])->name('shipping.detail');
    // Route::post('shipping/{id}/trash', 'Admin\ProductController@trash')->name('shipping.trash');
});
