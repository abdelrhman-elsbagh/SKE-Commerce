<?php

namespace App\Http\Controllers;
use App\Models\BusinessClient;
use App\Models\BusinessClientWallet;
use App\Models\BusinessPaymentMethod;
use App\Models\Config;
use App\Models\Currency;
use App\Models\Item;
use App\Models\News;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSubItem;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Post;
use App\Models\Slider;
use App\Models\SubItem;
use App\Models\TermsConditions;
use App\Models\User;
use App\Models\UserWallet;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::with('media')->get();
        $config = Config::with('media')->first();
        $items = Item::with('category', 'media')->get();
        $news = News::first();

        $user = Auth::guard('web')->user();
        if (!$user) {
            $user = Auth::guard('business_client')->user();
        }

        $favoritesCount = 0;
        if (Auth::guard('web')->user()) {
            $favoritesCount = $user->favorites()->count() ?? 0;

            if($user->feeGroup){
                $config->fee = $user->feeGroup->fee;
            }

        }

        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        View::share('config', $config);
        View::share('favoritesCount', $favoritesCount);

        $categorizedItems = $items->groupBy(function($item) {
            return $item->category->name;
        });

        return view('front.index', [
            'categorizedItems' => $categorizedItems,
            'sliders' => $sliders,
            'config' => $config,
            'paymentMethods' => $paymentMethods,
            'user' => $user,
            'news' => $news,
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
        $orders = $user->orders()->with('subItems.subItem.item.media')->orderBy('created_at', 'DESC')->get();
        $activeOrders = $user->orders()->with('subItems.subItem.item.media')->where('status', 'active')->get();
        $purchaseRequests = $user->purchaseRequests()->latest()->take(5)->get();

        // Calculate total money of orders
        $totalOrderMoney = $activeOrders->sum('total');

        // Calculate total amount of purchase requests
        $totalPurchaseRequestAmount = $purchaseRequests->sum('amount');

        $paymentMethods = PaymentMethod::where('status', 'active')->orderBy('created_at', 'DESC')->get();

        $feeGroup = null;
        if($user->feeGroup) {
            $feeGroup = $user->feeGroup;
        }

        return view('front.wallet', [
            'wallet' => $wallet,
            'orders' => $orders,
            'purchaseRequests' => $purchaseRequests,
            'paymentMethods' => $paymentMethods,
            'totalOrderMoney' => $totalOrderMoney,
            'totalPurchaseRequestAmount' => $totalPurchaseRequestAmount,
            'user' => $user,
            'feeGroup' => $feeGroup,

        ]);
    }
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

        $posts = Post::orderBy('created_at', 'DESC')->get();
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
        $currencies = Currency::all();
        return view('front.register', compact('config', 'currencies'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'currency_id' => $request->currency_id,
            'password' => Hash::make($request->password),
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
        $config = Config::with('media')->first();
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        return view('front.login', compact('config'));
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
        return view('front.favourites', compact('userFavorites', 'paymentMethods'));
    }

    public function purchase_order(Request $request)
    {
        $request->validate([
            'sub_item_id' => 'required|exists:sub_items,id',
            'service_id' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        // Retrieve the user's wallet
        $wallet = UserWallet::where('user_id', $user->id)->first();
        if (!$wallet) {
            return response()->json(['success' => false, 'message' => 'Wallet not found'], 404);
        }

        // Retrieve the selected sub-item
        $subItem = SubItem::findOrFail($request->sub_item_id);

        // Retrieve the fee percentage from the config
        $config = Config::first(); // Assuming you have a Config model to fetch the fee percentage
        if($user->feeGroup){
            $config->fee = $user->feeGroup->fee;
        }

        $feePercentage = $config->fee;

        // Calculate the total price including the fee
        $totalPrice = $subItem->price + ($subItem->price * $feePercentage / 100);

        // Check if the user has enough balance
        if ($wallet->balance < $totalPrice) {
            return response()->json(['success' => false, 'message' => 'Insufficient balance in wallet'], 400);
        }

        // Deduct the amount from the user's wallet
        $wallet->balance -= $totalPrice;
        $wallet->save();


        // Create the order
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $totalPrice,
            'status' => 'pending',
        ]);



        // Create the order sub item
        $order_sub_item = OrderSubItem::create([
            'order_id' => $order->id,
            'sub_item_id' => $subItem->id,
            'price' => $subItem->price + ($subItem->price * $feePercentage / 100),
            'service_id' => $request->service_id ?? null,
        ]);

        return response()->json(['success' => true, 'message' => 'Purchase successful', 'order' => $order], 200);
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

    public function item(Request $request, $id)
    {
        $config = Config::with('media')->first();
        $user = Auth::user();
        $favoritesCount = 0;

        if ($user) {
            $favoritesCount = $user->favorites()->count();
            if($user->feeGroup){
                $config->fee = $user->feeGroup->fee;
            }
        }
        View::share('favoritesCount', $favoritesCount);

        $item = Item::with(['subItems.orderSubItem', 'subItems.media', 'media', 'tags'])->findOrFail($id);
        // Check if user has a currency and adjust sub_item prices accordingly
        if ($user && $user->currency) {
            $currencyPrice = $user->currency->price;
            foreach ($item->subItems as $subItem) {
                $subItem->price = $subItem->price * $currencyPrice;
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
        ]);

        $user =  BusinessClient::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'business_name' => $request->business_name,
            'phone' => $request->phone,
            'address' => $request->address,
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
}
