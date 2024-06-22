<?php

namespace App\Http\Controllers;
use App\Models\BusinessClient;
use App\Models\BusinessClientWallet;
use App\Models\Config;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderSubItem;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Slider;
use App\Models\SubItem;
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
        $user = Auth::user();

        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
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
            'paymentMethods' => $paymentMethods
        ]);
    }


    public function wallet(Request $request)
    {
        $config = Config::with('media')->first();
        View::share('config', $config);

        $user = Auth::user();
        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);
        View::share('config', $config);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $wallet = UserWallet::where('user_id', $user->id)->firstOrFail();
        $orders = $user->orders()->with('subItems.subItem.item.media')->get();
        $purchaseRequests = $user->purchaseRequests()->latest()->take(5)->get();

        $paymentMethods = PaymentMethod::where('status', 'active')->get();
        return view('front.wallet', ['wallet' => $wallet, 'orders' => $orders, 'purchaseRequests' => $purchaseRequests,
            'paymentMethods' => $paymentMethods]);
    }




    public function register_page(Request $request)
    {
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        return view('front.register');
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
            'password' => Hash::make($request->password),
        ]);

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

        return view('front.profile', compact('user', 'orders', 'purchaseRequests', 'paymentMethods'));
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
            'status' => 'active',
        ]);

        // Create the order sub item
        OrderSubItem::create([
            'order_id' => $order->id,
            'sub_item_id' => $subItem->id,
            'price' => $subItem->price,
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
        return view('front.payment-methods', ['paymentMethods' => $paymentMethods]);
    }

    public function item(Request $request, $id)
    {
        $user = Auth::user();
        $favoritesCount = 0;
        if ($user) {
            $favoritesCount = $user->favorites()->count();
        }
        View::share('favoritesCount', $favoritesCount);
        $config = Config::with('media')->first();
        $item = Item::with(['subItems', 'subItems.media', 'media', 'tags'])->findOrFail($id);
        $userFavorites = [];
        if(Auth::user()){

            $userFavorites = Auth::user()->favorites->pluck('sub_item_id')->toArray();
        }
        $paymentMethods = PaymentMethod::where('status', 'active')->get();

        return view('front.item', compact('item', 'config', 'userFavorites', 'paymentMethods'));
    }

    public function logout(Request $request)
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
            // Authentication passed
            return response()->json(['success' => true, 'message' => 'Login successful']);
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
        }
    }
}
