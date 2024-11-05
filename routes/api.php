<?php

use App\Http\Controllers\ApiItemsController;
use App\Http\Controllers\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Inside routes/web.php or routes/api.php

Route::post('/make-http-request', [ApiItemsController::class, 'makeHttpRequest'])->name('make-http-request');
Route::post('/fetch-items', [ApiItemsController::class, 'fetchItems'])->name('api-items.fetch');
Route::post('/fetch-item', [ApiItemsController::class, 'fetchItem'])->name('api-items.fetchItem');
Route::post('/fetch-sub-item', [ApiItemsController::class, 'fetchSubItem'])->name('api-items.fetchSubItem');
Route::post('/store-api-order', [OrderController::class, 'storeApiOrder']);


//Route::post('/items/import', [ApiItemsController::class, 'importItems'])->name('api-items.import');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
