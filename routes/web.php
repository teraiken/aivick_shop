<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\ShippingFeeController;
use App\Http\Controllers\CardController;

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

Route::controller(ProductController::class)->name('products.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/products/{product}', 'show')->name('show');
});

Route::prefix('cart')->controller(CartController::class)->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::post('/update', 'update')->name('update');
    Route::post('/remove', 'remove')->name('remove');
    Route::post('/destroy', 'destroy')->name('destroy');
});

Route::middleware('auth')->group(function () {

    Route::prefix('my-page')->group(function () {

        Route::resource('addresses', AddressController::class);

        Route::prefix('card')->controller(CardController::class)->name('card.')->group(function () {
            Route::get('/', 'show')->name('show');
            Route::post('/', 'store')->name('store');
            Route::patch('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
        });

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::prefix('orders')->controller(OrderController::class)->name('orders.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{order}', 'show')->name('show');
        });
    });

    Route::prefix('orders')->controller(OrderController::class)->name('orders.')->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/confirm', 'confirm')->name('confirm');
        Route::get('/short', 'short')->name('short');
        Route::post('/', 'store')->name('store');
    });
});

require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware(['auth:admin'])->group(function () {

        Route::resource('products', AdminProductController::class);
        Route::resource('users', UserController::class);
        Route::resource('orders', AdminOrderController::class);
        Route::resource('admins', AdminController::class);
        Route::resource('areas', AreaController::class);

        Route::prefix('shippingFees')->controller(ShippingFeeController::class)->name('shippingFees.')->group(function () {
            Route::get('/create/{area}', 'create')->name('create');
            Route::post('/{area}', 'store')->name('store');
            Route::get('/{shippingFee}/edit', 'edit')->name('edit');
            Route::patch('/{shippingFee}', 'update')->name('update');
            Route::delete('/{shippingFee}', 'destroy')->name('destroy');
        });

        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [AdminProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__ . '/admin.php';
});
