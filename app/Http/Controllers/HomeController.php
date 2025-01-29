<?php

namespace App\Http\Controllers;
use App\Models\BusinessClient;
use App\Models\BusinessClientWallet;
use App\Models\BusinessPaymentMethod;
use App\Models\ClientStore;
use App\Models\Config;
use App\Models\Currency;
use App\Models\FeeGroup;
use App\Models\Footer;
use App\Models\Item;
use App\Models\News;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSubItem;
use App\Models\Page;
use App\Models\Partner;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Post;
use App\Models\ResponsesLog;
use App\Models\Slider;
use App\Models\SubItem;
use App\Models\TermsConditions;
use App\Models\User;
use App\Models\UserWallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::with('media')->get();
        $config = Config::with('media')->first();
        $items = Item::with('category', 'media')
            ->orderBy('order')         // Sort by order
            ->orderBy('updated_at', 'desc') // Sort by updated_at for items with same order
            ->get();
//        $items = Item::with('category', 'media')->get();
        $news = News::first();

        $user = Auth::guard('web')->user();
        if (!$user) {
            $user = Auth::guard('business_client')->user();
        }

        $favoritesCount = 0;
        $latestUnreadNotification = null;
        if (Auth::guard('web')->user()) {
            $favoritesCount = $user->favorites()->count() ?? 0;

            if ($user->feeGroup) {
                $config->fee = $user->feeGroup->fee;
            }

            // Fetch the latest unread notification for the user
            $latestUnreadNotification = Notification::whereDoesntHave('usersWhoRead', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->latest()->first();
        }

        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        View::share('config', $config);
        View::share('favoritesCount', $favoritesCount);


//        $categorizedItems = $items->groupBy(function ($item) {
//            return App::getLocale() === 'ar' && $item->category->ar_name
//                ? $item->category->ar_name // Use Arabic category name if the language is Arabic
//                : $item->category->name;
//        });

        $categorizedItems = $items
            // First, sort the items by order and updated_at
            ->sortBy(function ($item) {
                return [$item->order, $item->updated_at];
            })
            // Then, group by category name
            ->groupBy(function ($item) {
                return App::getLocale() === 'ar' && $item->category->ar_name
                    ? $item->category->ar_name // Use Arabic category name if the language is Arabic
                    : $item->category->name;
            });

        // Now sort the categories by the order and updated_at of the first item in the group
        $categorizedItems = $categorizedItems->sortBy(function ($items, $categoryName) {
            // Get the first item in the group to access the category order and updated_at
            $category = $items->first()->category;
            return [$category->order, $category->updated_at];  // Sort categories by order and updated_at
        });



        $locale = app()->getLocale(); // Get the current locale
        $footerItems = Footer::with('media')->get()->groupBy(function ($item) use ($locale) {
            return $locale === 'ar' ? $item->ar_tag : $item->tag;
        });

        if($user){
            if( $user->status == 'inactive' ){

                return view('front.inactive-index', [
                    'categorizedItems' => $categorizedItems,
                    'sliders' => $sliders,
                    'config' => $config,
                    'paymentMethods' => $paymentMethods,
                    'user' => $user,
                    'news' => $news,
                    'latestUnreadNotification' => $latestUnreadNotification,
                    'footerItems' => $footerItems,
                ]);
            }
        }


        return view('front.index', [
            'categorizedItems' => $categorizedItems,
            'sliders' => $sliders,
            'config' => $config,
            'paymentMethods' => $paymentMethods,
            'user' => $user,
            'news' => $news,
            'latestUnreadNotification' => $latestUnreadNotification,
            'footerItems' => $footerItems,
        ]);
    }
    public function wallet(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $favoritesCount = $user->favorites()->count();
        View::share('favoritesCount', $favoritesCount);

        $wallet = UserWallet::where('user_id', $user->id)->firstOrFail();

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $ordersQuery = $user->orders()->with('subItems.subItem.item.media')->orderBy('created_at', 'DESC');

        // If no date range is provided, filter orders for the current month
        if (!$startDate && !$endDate) {
            $startDate = now()->today()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        // Apply the date range filter
        if ($startDate && $endDate) {
            $ordersQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $orders = $ordersQuery->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('partials.orders_list', compact('orders', 'user'))->render(),
            ]);
        }

        $activeOrders = $orders->where('status', 'active');
        $purchaseRequests = $user->purchaseRequests()->with('paymentMethod')->latest()->take(5)->get();

        $totalOrderMoney = $activeOrders->sum('total');
        $approvedPurchaseRequests = $user->purchaseRequests()
            ->with('paymentMethod')
            ->where('status', 'approved')
            ->get();
        $totalPurchaseRequestAmount = $approvedPurchaseRequests->sum('amount');

        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();

        $feeGroup = $user->feeGroup ?? null;

        return view('front.wallet', [
            'wallet' => $wallet,
            'orders' => $orders,
            'purchaseRequests' => $purchaseRequests,
            'paymentMethods' => $paymentMethods,
            'totalOrderMoney' => $totalOrderMoney,
            'totalPurchaseRequestAmount' => $totalPurchaseRequestAmount,
            'user' => $user,
            'feeGroup' => $feeGroup,
            'endDate' => $endDate,
            'startDate' => $startDate,
        ]);
    }

//    public function wallet(Request $request)
//    {
//        $config = Config::with('media')->first();
//        View::share('config', $config);
//
//        $user = Auth::user();
//        if (!$user) {
//            return redirect()->route('login');
//        }
//
//        $favoritesCount = $user->favorites()->count();
//        View::share('favoritesCount', $favoritesCount);
//
//        $wallet = UserWallet::where('user_id', $user->id)->firstOrFail();
//        $orders = $user->orders()->with('subItems.subItem.item.media')->orderBy('created_at', 'DESC')->get();
//        $activeOrders = $user->orders()->with('subItems.subItem.item.media')->where('status', 'active')->get();
//        $purchaseRequests = $user->purchaseRequests()->with('paymentMethod')->latest()->take(5)->get();
//
//        // Calculate total money of orders
//        $totalOrderMoney = $activeOrders->sum('total');
//
//        // Calculate total amount of purchase requests
//        $approvedPurchaseRequests = $user->purchaseRequests()
//            ->with('paymentMethod')
//            ->where('status', 'approved')
//            ->get();
//        $totalPurchaseRequestAmount = $approvedPurchaseRequests->sum('amount');
//
//        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();
//
//        $feeGroup = null;
//        if($user->feeGroup) {
//            $feeGroup = $user->feeGroup;
//        }
//
//        return view('front.wallet', [
//            'wallet' => $wallet,
//            'orders' => $orders,
//            'purchaseRequests' => $purchaseRequests,
//            'paymentMethods' => $paymentMethods,
//            'totalOrderMoney' => $totalOrderMoney,
//            'totalPurchaseRequestAmount' => $totalPurchaseRequestAmount,
//            'user' => $user,
//            'feeGroup' => $feeGroup,
//
//        ]);
//    }
    public function posts(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $favoritesCount = $user->favorites()->count();
        View::share('favoritesCount', $favoritesCount);

        $posts = Post::withCount(['likes', 'dislikes'])->orderBy('created_at', 'DESC')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();

        return view('front.posts', [
            'posts' => $posts,
            'paymentMethods' => $paymentMethods,
            'user' => $user,
        ]);
    }

    public function terms_page(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route('sign-in');
        }

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        $terms = TermsConditions::firstOrFail();

        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();

        return view('front.terms_conditions', [
            'terms' => $terms,
            'paymentMethods' => $paymentMethods,
            'user' => $user
        ]);
    }

    public function business_wallet(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $businessClient = Auth::guard('business_client')->user();
        if (!$businessClient) {
            return redirect()->route('business-sign-in');
        }

        $wallet = BusinessClientWallet::where('business_client_id', $businessClient->id)->firstOrFail();
        $subscriptions = $businessClient->subscriptions()->with('plan')->get();
        $purchaseRequests = $businessClient->businessPurchaseRequests()->latest()->take(5)->get();
        $paymentMethods = $businessClient->businessPaymentMethods()->where('status', 'active')->get();

        return view('front.business_wallet', [
            'wallet' => $wallet,
            'subscriptions' => $subscriptions,
            'purchaseRequests' => $purchaseRequests,
            'paymentMethods' => $paymentMethods,
        ]);
    }


    public function register_page(Request $request)
    {
        $config = Config::with('media')->first();
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        $currencies = Currency::where('status', 'active')->get();
        return view('front.register', compact('config', 'currencies'));
    }
    public function register_partner_page(Request $request)
    {
        $config = Config::with('media')->first();
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        $currencies = Currency::where('status', 'active')->get();
        return view('front.register-partner', compact('config', 'currencies'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric',
            'country' => 'required|string',
            'currency_id' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $fee = FeeGroup::where('name', 'Default')->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'currency_id' => $request->currency_id,
            'fee_group_id' => $fee->id, // Default
            'address' => $request->country,
            'password' => Hash::make($request->password),
            'status' => 'active',
        ]);

        $user->assignRole('User');

        Auth::login($user);

        UserWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        return response()->json(['message' => 'User registered successfully']);
    }

    public function registerPartner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|numeric',
            'country' => 'required|string',
            'domain'  => 'required|string',
            'company'  => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $usd = Currency::where('currency', 'USD')->firstOrFail();
        $fee = FeeGroup::where('name', 'Default')->firstOrFail();

        $normalizedDomain = rtrim($request->domain, '/');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'currency_id' => $usd->id, // USD
            'fee_group_id' => $fee->id, // Default
            'address' => $request->country,
            'company' => $request->company,
            'domain' => $normalizedDomain,
            'password' => Hash::make($request->password),
            'status' => 'inactive',
            'is_external' => true,
            'secret_key' => Str::uuid()->toString(),
        ]);

        $user->assignRole('User');

        Auth::login($user);

        UserWallet::create([
            'user_id' => $user->id,
            'balance' => 0,
        ]);

        return response()->json(['message' => 'User registered successfully']);
    }


    public function login_page(Request $request)
    {

        $paymentMethods = PaymentMethod::where('status', 'active')->get();
        $config = Config::with('media')->first();
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        return view('front.login', compact('config', 'paymentMethods'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log the user in
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
            // If successful, redirect to their intended location
            return redirect()->route('home');  // Change 'dashboard' to wherever you want users to go after login
        }

        // If unsuccessful, redirect back with input (except for password)
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'sub_item_id' => 'required|exists:sub_items,id',
        ]);

        return redirect()->route('home')->with('success', 'Purchase successful!');
    }

    public function profile(Request $request)
    {
        $config = Config::with('media')->first();
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }
        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        View::share('config', $config);

        $orders = $user->orders()->with('subItems.subItem.media')->get();
        $purchaseRequests = $user->purchaseRequests()->with('media')->get();

        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        $feeGroup = null;
        if($user->feeGroup) {
            $feeGroup = $user->feeGroup;
        }

        return view('front.profile', compact('user', 'orders', 'purchaseRequests', 'paymentMethods', 'feeGroup'));
    }
    public function business_profile(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $businessClient = Auth::guard('business_client')->user();
        if (!$businessClient) {
            return redirect()->route('business-sign-in');
        }

        $favoritesCount = 0;
        $paymentMethods = BusinessPaymentMethod::where('status', 'active')->get();
        View::share('favoritesCount', $favoritesCount);

        $subscriptions = $businessClient->subscriptions()->with('plan.media')->get();
        $purchaseRequests = $businessClient->businessPurchaseRequests()->with('media')->get();

        return view('front.business_profile', compact('businessClient', 'subscriptions', 'purchaseRequests', 'paymentMethods'));
    }

    public function favourites(Request $request)
    {
        $user = Auth::user();
        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);
        $config = Config::with('media')->first();
        $userFavorites = Auth::user()->favorites()->with(['item', 'subItem.item', 'item.media', 'subItem.media'])->get();
        View::share('config', $config);
        $paymentMethods = PaymentMethod::where('status', 'active')->get();
        return view('front.favourites', compact('userFavorites', 'paymentMethods', 'user'));
    }

    public function purchase_order(Request $request)
    {
        $request->validate([
            'sub_item_id' => 'required|exists:sub_items,id',
            'service_id' => 'required|string',
            'custom_amount' => 'required|integer|min:0|nullable',
        ]);

        // Retrieve the selected sub-item
        $subItem = SubItem::findOrFail($request->sub_item_id);
        $orderUuid = (string) Str::uuid();

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        if ($subItem->status !== 'active') {
            return response()->json(['success' => false, 'message' => 'This sub-item is inactive and cannot be purchased'], 400);
        }

        // Retrieve the user's wallet
        $wallet = UserWallet::where('user_id', $user->id)->first();
        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'Wallet not found'], 404);
        }

        $currencyPrice = 1;
        if ($user && $user->currency) {
            $currencyPrice = $user->currency->price;
        }

        // Retrieve the fee percentage from the config
        $config = Config::first();
        $fee_name = "Default";
        if($user->feeGroup){
            $config->fee = $user->feeGroup->fee;
            $fee_name = $user->feeGroup->name ?? "";
        }
        $feePercentage = $config->fee;

        $sub_price = floatval($subItem->price * $currencyPrice);
        $fe_price = floatval($sub_price * $feePercentage / 100);

        $totalPrice = $sub_price + $fe_price;

        // Check if the user has enough balance
        if ($wallet->balance < $totalPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient balance in wallet'], 400);
        }


        $external_order_id = 0;
        // Check if the sub-item is external
        if ($subItem->external_id && !$subItem->out_flag) {
            // External item logic
            try {
                // Call the external API (storeApiOrder)
                $response = $this->sendExternalOrder($subItem, $request->service_id);
                if (!$response['success']) {
                    return response()->json(['success' => false, 'message' => 'Failed to process external order'], 400);
                }

                $external_data = $response['data'];
                $external_order_id = $external_data['order_id'];

            } catch (\Exception $e) {
                Log::error($e->getMessage());
                return response()->json(['success' => false, 'message' => 'Error processing external sub-item'], 500);
            }
        }

        if ($subItem->external_id && $subItem->out_flag) {
            try {
                // Prepare the external API request data
                $client = ClientStore::where('domain', $subItem->domain)->where('status', 'active')->first();
                $apiToken = $client->secret_key;
                $quantity = $subItem->is_custom == 1 ? $request->custom_amount : $subItem->amount;
                $serviceId = $request->service_id;

                $url = "{$subItem->domain}/client/api/newOrder/{$subItem->external_id}/params";
                $queryParams = [
                    'qty' => $quantity,
                    'playerId' => $serviceId,
                    'order_uuid' => $orderUuid,
                ];


                $response = Http::withHeaders([
                    'api-token' => $apiToken,
                ])->get($url, $queryParams);

                try {
                    ResponsesLog::create([
                        'type' => 'API Response',
                        'request_data' => json_encode($request->all()),
                        'response_data' => json_encode($response->json()),
                        'error_message' => $response->failed() ? 'Failed to process request' : null,
                    ]);
                }
                catch (\Exception $e) {Log::error($e->getMessage());}

                // Check the response
                if ($response->successful()) {
                    $responseData = $response->json();
                    $external_order_id = $responseData['data']['order_id'] ?? null;
                } else {
                    return response()->json(['success' => false, 'message' => 'Failed to communicate with external API'], 500);
                }
            } catch (\Exception $e) {
                Log::error('External API error: ' . $e->getMessage());
                return response()->json(['success' => false, 'message' => 'Error processing external API request'], 500);
            }
        }

        $order_amount = $subItem->amount;

        if ($subItem->is_custom == 1) {
            $fe_price = floatval($sub_price * $feePercentage / 100);
            $order_amount = $request->custom_amount;
            $unitAmount = $subItem->amount; // Each 100 units
            $basePrice = $subItem->price; // Price for each 100 units

            // Calculate price based on custom amount
            $sub_price = ($order_amount / $unitAmount) * $basePrice;
            $fe_price = floatval($sub_price * $feePercentage / 100);
        }



        // Deduct the amount from the user's wallet
        $before_balance = $wallet->balance;
        $wallet->balance -= $totalPrice;
        $wallet->save();

        $after_balance = $wallet->balance;

        $order_type = $subItem->external_id ? "API Order" : "Client Order";

        $prepared_order = [
            'user_id' => $user->id,
            'total' => $totalPrice,
            'status' => 'pending',
            'user_email' => $user->email ?? null,
            'user_name' => $user->name ?? null,
            'user_phone' => $user->phone ?? null,
            'item_price' => $sub_price ?? null,
            'item_fee' => $fe_price ?? null,
            'fee_name' => $fee_name ?? null,
            'item_name' => $subItem->item->name ?? null,
            'sub_item_name' => $subItem->name ?? null,
            'service_id' => $request->service_id ?? null,
            'wallet_before' => $before_balance ?? null,
            'wallet_after' => $after_balance ?? null,
            'amount' => $order_amount,
            'currency_id' => $user->currency_id ?? null,
            'item_id' => $subItem->item->id ?? null,
            'sub_item_id' => $subItem->id ?? null,
            'revenue' => $fe_price ?? null,
            'is_external' => $subItem->external_id ? true : false,
            'order_type' => $order_type,
            'external_order_id' => $external_order_id ?? null,
            'uuid' => $orderUuid,

        ];

        // Create the order
        $order = Order::create($prepared_order);

        // Create the order sub-item
        $order_sub_item = OrderSubItem::create([
            'order_id' => $order->id,
            'sub_item_id' => $subItem->id,
            'price' => $sub_price,
            'service_id' => $request->service_id ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Purchase successful', 'order' => $order], 200);
    }

    protected function sendExternalOrder($subItem, $service_id)
    {
        // Use the user associated with the SubItem as the "local user" for the external system
        $localUser = $subItem->user;

        if (!$localUser) {
            return ['success' => false, 'message' => 'Local user associated with sub-item not found'];
        }

        $revenue = floatval($subItem->price - $subItem->original_price);

        // Prepare the payload for the external API request
        $payload = [
            'secret_key' => $subItem->clientStore->secret_key,
            'external_user_id' => $subItem->external_user_id,
            'service_id' => $service_id,
            'external_id' => $subItem->external_id, // sub item external_id
            'is_external' => true,
            'order_type' => "API Order",
            'status' => "pending",
            'amount' => $subItem->amount,
            'sub_item_name' => $subItem->name,
            'sub_item_id' => $subItem->id,
            'item_id' => null ?? $subItem->item->id,
            'item_name' => $subItem->item->name,
            'revenue' => $revenue,
            'total' => floatval($subItem->price),
        ];


        $url = $subItem->domain . '/api/store-api-order';
        // Send the request to the external API using the domain from SubItem
        try {

            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post($url, $payload);

            // Check if the response was successful
            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] == 'success') {
                    return ['success' => true, 'message' => 'External order processed successfully', 'data' => $responseData['data']];
                }
                return ['success' => false, 'message' => 'Failed to process external order'];

            } else {
                Log::error('Failed to process external order', ['response' => $response->body()]);
                return ['success' => false, 'message' => 'Failed to process external order'];
            }

        } catch (\Exception $e) {
            Log::error('Error processing external order', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => 'Error processing external order'];
        }
    }

    public function plans(Request $request)
    {
        $user = Auth::user();

        $config = Config::with('media')->first();
        View::share('config', $config);

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        $plans = Plan::with('features')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->get();
        return view('front.plans', compact('plans', 'paymentMethods'));
    }
    public function api(Request $request)
    {
        if(!Auth::user()->is_external) {
            return redirect()->route('home');
        }
        $user = Auth::user();

        $config = Config::with('media')->first();
        View::share('config', $config);

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        $paymentMethods = PaymentMethod::where('status', 'active')->get();
        return view('front.api', compact('paymentMethods'));
    }

    public function payment_methods(Request $request)
    {
        $user = Auth::user();
        $config = Config::with('media')->first();
        View::share('config', $config);

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        $paymentMethods = PaymentMethod::with('media')->get();
        return view('front.payment-methods', ['paymentMethods' => $paymentMethods, 'user' => $user]);
    }

    public function partners(Request $request)
    {
        $user = Auth::user();
        $config = Config::with('media')->first();
        View::share('config', $config);

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);

        $partners = Partner::with('media')->get();
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('front.partners', ['partners' => $partners, 'user' => $user, 'paymentMethods' => $paymentMethods]);
    }

    public function item(Request $request, $id)
    {
        $config = Config::with('media')->first();
        $user = Auth::user();
        $favoritesCount = 0;

        if ($user) {
            $favoritesCount = $user->favorites()->count();
            if ($user->feeGroup) {
                $config->fee = $user->feeGroup->fee;
            }
        }
        View::share('favoritesCount', $favoritesCount);


        $item = Item::with(['subItems.orderSubItem', 'subItems.media', 'media', 'tags'])->findOrFail($id);

        // Check if user has a currency and adjust sub_item prices accordingly
        if ($user && $user->currency) {
            $config->fee = $user->feeGroup->fee;
            $currencyPrice = $user->currency->price;
            foreach ($item->subItems as $subItem) {
                // If the subItem has an external_id, fetch price and amount from external API
                if ($subItem->external_id && !$subItem->out_flag) {
                    // Construct the full URL by combining the domain and the API path
                    $url = $subItem->domain . '/api/fetch-sub-item';

                    $external_usr_secret = $subItem->clientStore->secret_key ?? null;
                    $own_usr_secret = User::where('id', $subItem->user_id)->first()->secret_key ?? null;
//                    $fe_amount = round($subItem->fee_amount, 2);
                    $fe_amount = floatval($subItem->fee_amount);


                    // Prepare the data to match the cURL request format
                    $data = [
                        'external_id' => intval($subItem->external_id),
                        'destination_key' => $external_usr_secret,
                        'source_key' => $own_usr_secret,
                    ];

                    // Send POST request with the correct headers and data
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json'
                    ])->post($url, $data);

                    // Log the request for debugging
                    if ($response->successful()) {
                        $updated_data = $response->json('sub_item');
                        $subItem->price = $updated_data['price'] * $currencyPrice;
                        $subItem->status = $updated_data['status'];
                        $subItem->amount = $updated_data['amount'];
                        $subItem->fee_amount = $fe_amount;
                        $subItem->original_price = $updated_data['origin_price'];
                        $subItem->external_item_id = $updated_data['item_id'];
                        $subItem->save();
                    }
                } else {
                    // Regular price adjustment based on user currency
                    $subItem->price = floatval($subItem->price * $currencyPrice);
                }
            }
        }

        $userFavorites = [];
        if (Auth::check()) {
            $userFavorites = Auth::user()->favorites->pluck('sub_item_id')->toArray();
        }

        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('front.item', compact('item', 'config', 'userFavorites', 'paymentMethods', 'user'));
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('sign-in');
    }

    public function business_logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('sign-in');
    }

    public function register_business_page(Request $request)
    {
        $config = Config::with('media')->first();
        if (!$config->super_admin ==1) {
            return redirect()->route('home');
        }
        return view('front.business_register', compact('config'));
    }


    public function register_business(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:business_clients',
            'password' => 'required|string|min:8|confirmed',
            'business_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);

        $user =  BusinessClient::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'business_name' => $request->business_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
        ]);

        BusinessClientWallet::create([
            'business_client_id' => $user->id,
            'balance' => 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Registration successful']);
    }

    public function login_business_page(Request $request)
    {
        $config = Config::with('media')->first();
        if (!$config->super_admin ==1) {
            return redirect()->route('home');
        }
        return view('front.business_login', compact('config'));
    }


    public function login_business(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('business_client')->attempt($credentials)) {
            return redirect()->route('home');
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }

    public function showPageBySlug(Request $request, $slug)
    {
        // Fetch and share config data
        $config = Config::with('media')->first();
        View::share('config', $config);

        // Get the authenticated user, if not redirect to sign-in
        $user = Auth::guard('web')->user();
        if (!$user) {
            return redirect()->route('sign-in');
        }

        // Count user's favorites if logged in
        $favoritesCount = $user->favorites()->count();
        View::share('favoritesCount', $favoritesCount);

        // Fetch the page by slug, return 404 if not found
        $page = Page::where('slug', $slug)->firstOrFail();

        // Get active payment methods
        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();

        // Pass data to the view
        return view('front.page', [
            'page' => $page,
            'paymentMethods' => $paymentMethods,
            'user' => $user
        ]);
    }
}
