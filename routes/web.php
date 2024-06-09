<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessClientWalletController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientPurchaseRequestController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\DiamondRateController;
use App\Http\Controllers\DiamondRatesController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseRequestController;
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
Route::get('sign-in', [HomeController::class, 'login_page'])->name('sign-in');
Route::get('profile', [HomeController::class, 'profile'])->name('profile');
Route::get('favourites', [HomeController::class, 'favourites'])->name('favourites');
Route::get('wallet', [HomeController::class, 'wallet'])->name('wallet');
Route::get('/item/{id}', [HomeController::class, 'item'])->name('item.show');
Route::post('purchase', [HomeController::class, 'purchase'])->name('purchase');
Route::post('login', [HomeController::class, 'login']);
Route::post('register', [HomeController::class, 'register'])->name('register');
Route::post('/purchase-request', [PurchaseController::class, 'request'])->name('purchase.request');
Route::post('/favorites/add', [FavoriteController::class, 'add'])->name('favorites.add');




Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    // Define the admin home route
    Route::get('/home', [RoutingController::class, 'index'])->name('admin.home');

    Route::resource('purchase-requests', ClientPurchaseRequestController::class);

    Route::resource('user-wallets', UserWalletController::class);
    Route::resource('users', UserController::class);
    Route::resource('sliders', SliderController::class);
    Route::get('configs/edit', [ConfigController::class, 'edit'])->name('configs.edit');
    Route::put('configs/{id}', [ConfigController::class, 'update'])->name('configs.update');
    Route::resource('business-client-wallets', BusinessClientWalletController::class)->names('business-client-wallets');
    Route::resource('categories', CategoryController::class);
    Route::resource('diamond-rates', DiamondRatesController::class)->names('diamond_rates');
    Route::resource('items', ItemController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('tags', TagController::class);

    // Generic wildcard routes
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
