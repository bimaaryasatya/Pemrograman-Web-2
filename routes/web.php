<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomepageController::class, 'index'])->name('home');
Route::get('products', [HomepageController::class, 'products'])->name('product.show');
Route::get('product/{slug}', [HomepageController::class, 'product'])->name('product.show');
Route::get('categories', [HomepageController::class, 'categories']);
Route::get('category/{slug}', [HomepageController::class, 'category']);
Route::get('cart', [HomepageController::class, 'cart'])->name('cart.index');
Route::get('checkout', [HomepageController::class, 'checkout']);

// Route::group(['middleware' => ['is_customer_login']], function () {
Route::controller(CartController::class)->group(function () {
    Route::post('cart/add', 'add')->name('cart.add');
    Route::delete('cart/remove/{id}', 'remove')->name('cart.remove');
    Route::patch('cart/update/{id}', 'update')->name('cart.update');
});
// });

Route::get('checkout/success', [HomepageController::class, 'checkout_success'])->name('checkout.success');
Route::get('checkout/failure', [HomepageController::class, 'checkout_failure'])->name('checkout.failure');
Route::get('checkout/thankyou', [HomepageController::class, 'checkout_thankyou'])->name('checkout.thankyou');
Route::get('checkout/thankyou/{order}', [HomepageController::class, 'checkout_thankyou'])->name('checkout.thankyou.order');
Route::get('checkout/thankyou/{order}/print', [HomepageController::class, 'checkout_thankyou_print'])->name('checkout.thankyou.print');
Route::get('checkout/thankyou/{order}/download', [HomepageController::class, 'checkout_thankyou_download'])->name('checkout.thankyou.download');
use Livewire\Volt\Volt;

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class)->names([
        'index'   => 'dashboard.products.index',
        'create'  => 'dashboard.products.create',
        'store'   => 'dashboard.products.store',
        'edit'    => 'dashboard.products.edit',
        'update'  => 'dashboard.products.update',
        'destroy' => 'dashboard.products.destroy',
    ]);
});

// route group untuk customer
Route::group(['prefix' => 'customer'], function () {
    Route::controller(CustomerAuthController::class)->group(function () {
        //tampilkan halaman login
        Route::get('login', 'login')->name('customer.login');
        //aksi login
        Route::post('login', 'store_login')->name('customer.store_login');
        //tampilkan halaman register
        Route::get('register', 'register')->name('customer.register');
        //aksi register
        Route::post('register', 'store_register')->name('customer.store_register');
        //aksi logout
        Route::post('logout', 'logout')->name('customer.logout');
    });
});
// end route customer

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
