<?php

use App\Http\Controllers\ItemStyleController;
use App\Http\Controllers\TicketCategoryController;
use App\Http\Controllers\TicketController;
use Illuminate\Http\Request;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApiItemsController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientStoreController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessClientWalletController;
use App\Http\Controllers\BusinessPurchaseRequestController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientPurchaseRequestController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiamondRatesController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FeeGroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserWalletController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use Laravel\Socialite\Facades\Socialite;


require __DIR__ . '/auth.php';


//Route::get('/abdel', [PermissionController::class, 'syncAdminGroupPermissions']);

/*
Route::get('auth/google', function (Request $request) {
    // Validate input parameters first
    $currency = $request->query('currency');
    $country = $request->query('country');
    $phone = $request->query('phone');

    // Check if all fields are present
    if (!$currency || !$country || !$phone) {
        return redirect()->back()->withErrors(['message' => 'All fields are required.']);
    }

    // Store data in session
    session([
        'google_currency' => $currency,
        'google_country' => $country,
        'google_phone' => $phone,
    ]);

    return Socialite::driver('google')->redirect();
})->name('google.login');

*/

// ✅ Google Register - Requires Currency, Country, Phone
Route::get('auth/google/register', function (Request $request) {
    if (!$request->has(['currency', 'country', 'phone'])) {
        return redirect()->back()->withErrors(['message' => 'Currency, country, and phone are required for registration.']);
    }

    session([
        'google_currency' => $request->query('currency'),
        'google_country' => $request->query('country'),
        'google_phone' => $request->query('phone'),
    ]);

    return Socialite::driver('google')->redirect();
})->name('google.register');

// ✅ Google Login - Does NOT Require Currency, Country, Phone
Route::get('auth/google/login', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

// ✅ Google Callback - Handles Both Register & Login
Route::get('auth/google/callback', [HomeController::class, 'googleAuth'])->name('google.callback');



//Route::get('auth/google/callback', [HomeController::class, 'googleRegister'])->name('google.callback');



Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('SetLocale');


Route::get('/change-language/{lang}', [LanguageController::class, 'changeLanguage'] )->name('change-language');



Route::get('registration', [HomeController::class, 'register_page'])->name('register-page');
Route::post('user-create-ticket', [HomeController::class, 'create_ticket'])->name('user-create-ticket');
Route::get('user-tickets', [HomeController::class, 'tickets'])->name('user-tickets');
Route::get('partner-registration', [HomeController::class, 'register_partner_page'])->name('register-partner');
Route::get('register-business', [HomeController::class, 'register_business_page'])->name('register-business');
Route::get('sign-in', [HomeController::class, 'login_page'])->name('sign-in');
Route::get('business-sign-in', [HomeController::class, 'login_business_page'])->name('business-sign-in');
Route::post('business-login', [HomeController::class, 'login_business'])->name('business-login');
Route::get('business-profile', [HomeController::class, 'business_profile'])->name('business-profile');
Route::get('business-wallet', [HomeController::class, 'business_wallet'])->name('business-wallet');
Route::get('plans', [HomeController::class, 'plans'])->name('plans-page');
Route::get('api', [HomeController::class, 'api'])->name('api');

Route::post('login', [HomeController::class, 'login']);
Route::post('register', [HomeController::class, 'register'])->name('register');
Route::post('google-register', [HomeController::class, 'googleRegister']);

Route::post('partner-register', [HomeController::class, 'registerPartner'])->name('partner-register');
Route::post('register_business', [HomeController::class, 'register_business'])->name('register_business');

Route::middleware(['auth', 'role:User', 'SetLocale'])->group(function () {
    Route::get('favourites', [HomeController::class, 'favourites'])->name('favourites');
    Route::get('posts', [HomeController::class, 'posts'])->name('posts');
    Route::get('wallet', [HomeController::class, 'wallet'])->name('wallet');
    Route::get('partners', [HomeController::class, 'partners'])->name('partners');
//    Route::get('payment-methods', [HomeController::class, 'payment_methods'])->name('payments-page');
    Route::get('/page/{slug}', [HomeController::class, 'showPageBySlug'])->name('page');
    Route::get('terms-conditions', [HomeController::class, 'terms_page'])->name('terms-page');
    Route::get('/item/{id}', [HomeController::class, 'item'])->name('item.show');
    Route::post('purchase', [HomeController::class, 'purchase'])->name('purchase');
    Route::post('purchase_order', [HomeController::class, 'purchase_order'])->name('purchase_order');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');
    Route::post('/purchase-request', [PurchaseController::class, 'request'])->name('purchase.request');
    Route::post('/favorites/add', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::post('/favorites/remove', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::post('/notifications/markAsRead/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/posts/{post}/like', [PostController::class, 'likePost'])->name('posts.like');
    Route::post('/posts/{post}/dislike', [PostController::class, 'dislikePost'])->name('posts.dislike');
});


Route::post('logout', [HomeController::class, 'logout'])->name('logout');
Route::post('business-logout', [HomeController::class, 'business_logout'])->name('business-logout');

Route::put('users/{id}', [UserController::class, 'profile_update'])->name('profile-update');

Route::get('/items', [ApiItemsController::class, 'allItems'])->name('api-items-all');

Route::get('/import-eko-products', [ItemController::class, 'fetchAndImportEkoStoreProducts'])->name('import.eko.products');


Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission:admin']], function () {


    Route::get('/permissions/sync-admin', [PermissionController::class, 'syncAdminGroupPermissions'])
        ->name('permissions.sync.admin')
        ->middleware(['auth', 'permission:admin']);

    Route::get('', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('index', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('clientStores', ClientStoreController::class);

    Route::get('/api-items/edit', [ApiItemsController::class, 'edit'])->name('api-items.edit');
    Route::post('/items/import', [ApiItemsController::class, 'importItems'])->name('items.import');
    Route::get('/fetch-eko-store-items', [ApiItemsController::class, 'fetchEkoStoreItems'])->name('fetch.eko.store.items');
    Route::post('/client-stores/integrate', [ClientStoreController::class, 'integrate'])->name('clientStores.integrate');
    Route::get('/modules-integration', [ClientStoreController::class, 'ekoIntegrate'])->name('stores.integrates');



    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
    Route::get('permissions/{id}', [PermissionController::class, 'show'])->name('permissions.show');
    Route::get('permissions/{id}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
    Route::put('permissions/{id}', [PermissionController::class, 'update'])->name('permissions.update');
    Route::delete('permissions/{id}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    Route::get('assign-permissions', [PermissionController::class, 'assignPermissionsForm'])->name('assign-permissions-form');
    Route::post('assign-permissions', [PermissionController::class, 'assignPermissions'])->name('assign-permissions');

//    Route::get('', [RoutingController::class, 'index'])->name('admin.home');
//    Route::get('/home', [RoutingController::class, 'index'])->name('admin.home');

    Route::resource('purchase-requests', ClientPurchaseRequestController::class);
    Route::resource('business-purchase-requests', BusinessPurchaseRequestController::class);

    Route::resource('clients', ClientController::class)->names('clients');
//    Route::delete('/clients/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');
    Route::resource('accounts', AccountController::class)->names('accounts');
    Route::get('/export', [AccountController::class, 'export'])->name(name: 'accounts.export');
    Route::get('/accounts/{id}/data', [AccountController::class, 'getAccountData'])->name('accounts.getData');


    Route::get('/clients-currencies', [ClientController::class, 'getCurrencies'])->name('clients.currencies');


    Route::resource('user-wallets', UserWalletController::class);
    Route::resource('users', UserController::class);
    Route::resource('sliders', SliderController::class);
    Route::resource('orders', OrderController::class);
    Route::get('orders-analytics', [OrderController::class, 'order_analytics'])->name('orders.analytics');

    Route::get('configs/edit', [ConfigController::class, 'edit'])->name('configs.edit');
    Route::put('configs/{id}', [ConfigController::class, 'update'])->name('configs.update');

    Route::get('item_styles/edit', [ItemStyleController::class, 'edit'])->name('item_styles.edit');
    Route::put('item_styles/{id}', [ItemStyleController::class, 'update'])->name('item_styles.update');

    Route::get('terms/edit', [TermsController::class, 'edit'])->name('terms.edit');
    Route::put('terms/{id}', [TermsController::class, 'update'])->name('terms.update');
    Route::resource('business-client-wallets', BusinessClientWalletController::class)->names('business-client-wallets');
    Route::resource('categories', CategoryController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('diamond-rates', DiamondRatesController::class)->names('diamond_rates');
    Route::resource('items', ItemController::class);
    Route::get('subitems/search', [ItemController::class, 'search'])->name('subitems.search');
    Route::post('subitems/move', [ItemController::class, 'move'])->name('subitems.move');
    Route::post('/items/delete-selected', [ItemController::class, 'deleteSelected'])->name('items.deleteSelected');


    Route::resource('plans', PlanController::class);
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('tags', TagController::class);
    Route::resource('footer', FooterController::class);
    Route::resource('fee-groups', FeeGroupController::class)->names('fee_groups');
    Route::resource('posts', PostController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('ticket_categories', TicketCategoryController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('notifications', NotificationController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('pages', PageController::class);

    Route::get('/order-export', [OrderController::class, 'export'])->name('orders.export');

    Route::get('news/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('news/{id}', [NewsController::class, 'update'])->name('news.update');

    Route::get('/profile', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Generic wildcard routes
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
