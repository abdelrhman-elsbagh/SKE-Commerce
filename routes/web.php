<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessClientWalletController;
use App\Http\Controllers\BusinessPurchaseRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientPurchaseRequestController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiamondRatesController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserWalletController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;


require __DIR__ . '/auth.php';

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('registration', [HomeController::class, 'register_page'])->name('register-page');
Route::get('register-business', [HomeController::class, 'register_business_page'])->name('register-business');
Route::get('sign-in', [HomeController::class, 'login_page'])->name('sign-in');
Route::get('business-sign-in', [HomeController::class, 'login_business_page'])->name('business-sign-in');
Route::post('business-login', [HomeController::class, 'login_business'])->name('business-login');
Route::get('profile', [HomeController::class, 'profile'])->name('profile');
Route::get('business-profile', [HomeController::class, 'business_profile'])->name('business-profile');
Route::get('favourites', [HomeController::class, 'favourites'])->name('favourites');
Route::get('wallet', [HomeController::class, 'wallet'])->name('wallet');
Route::get('business-wallet', [HomeController::class, 'business_wallet'])->name('business-wallet');
Route::get('plans', [HomeController::class, 'plans'])->name('plans-page');
Route::get('payment-methods', [HomeController::class, 'payment_methods'])->name('payments-page');
Route::get('/item/{id}', [HomeController::class, 'item'])->name('item.show');
Route::post('purchase', [HomeController::class, 'purchase'])->name('purchase');
Route::post('purchase_order', [HomeController::class, 'purchase_order'])->name('purchase_order');
Route::post('login', [HomeController::class, 'login']);
Route::post('register', [HomeController::class, 'register'])->name('register');
Route::post('register_business', [HomeController::class, 'register_business'])->name('register_business');
Route::post('/purchase-request', [PurchaseController::class, 'request'])->name('purchase.request');
Route::post('/favorites/add', [FavoriteController::class, 'add'])->name('favorites.add');

Route::post('logout', [HomeController::class, 'logout'])->name('logout');
Route::post('business-logout', [HomeController::class, 'business_logout'])->name('business-logout');

Route::put('users/{id}', [UserController::class, 'profile_update'])->name('profile-update');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    // Define the admin home route

    Route::get('', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('index', [DashboardController::class, 'index'])->name('dashboard');

//    Route::get('', [RoutingController::class, 'index'])->name('admin.home');
//    Route::get('/home', [RoutingController::class, 'index'])->name('admin.home');

    Route::resource('purchase-requests', ClientPurchaseRequestController::class);
    Route::resource('business-purchase-requests', BusinessPurchaseRequestController::class);

    Route::resource('user-wallets', UserWalletController::class);
    Route::resource('users', UserController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('orders', OrderController::class);
    Route::get('configs/edit', [ConfigController::class, 'edit'])->name('configs.edit');
    Route::put('configs/{id}', [ConfigController::class, 'update'])->name('configs.update');
    Route::resource('business-client-wallets', BusinessClientWalletController::class)->names('business-client-wallets');
    Route::resource('categories', CategoryController::class);
    Route::resource('diamond-rates', DiamondRatesController::class)->names('diamond_rates');
    Route::resource('items', ItemController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('tags', TagController::class);

    Route::get('news/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');

    // Generic wildcard routes
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
