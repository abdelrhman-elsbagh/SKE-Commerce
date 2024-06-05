<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;

require __DIR__ . '/auth.php';

//Route::get('/', [HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    // Define the admin home route
    Route::get('/home', [RoutingController::class, 'index'])->name('admin.home');

    // Define the specific resource routes for categories first
    Route::resource('categories', CategoryController::class);
    Route::resource('items', ItemController::class);
    Route::resource('plans', PlanController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('tags', TagController::class);

    // Generic wildcard routes
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
