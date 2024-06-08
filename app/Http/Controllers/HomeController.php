<?php

namespace App\Http\Controllers;
use App\Models\Config;
use App\Models\Item;
use App\Models\Slider;
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

        // Retrieve all items with their associated categories and media
        $items = Item::with('category', 'media')->get();

        View::share('config', $config);

        // Organize items by category
        $categorizedItems = $items->groupBy(function($item) {
            return $item->category->name;
        });

        // Pass the categorized items with media to the view
        return view('front.index', ['categorizedItems' => $categorizedItems, 'sliders' => $sliders, 'config' => $config]);
    }

    public function wallet(Request $request)
    {
        // Ensure the user is authenticated
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Retrieve the user's wallet information
        $wallet = UserWallet::where('user_id', $user->id)->firstOrFail(); // Use firstOrFail to handle no wallet case

        // Return the wallet view and pass the wallet data
        return view('front.wallet', ['wallet' => $wallet]);
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

        return response()->json(['message' => 'User registered successfully']);
    }

    public function login_page(Request $request)
    {
        /*$user = Auth::user();
        if (!$user) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }*/
        return view('front.login');
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
            return redirect()->intended('dashboard');  // Change 'dashboard' to wherever you want users to go after login
        }

        // If unsuccessful, redirect back with input (except for password)
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function item(Request $request, $id)
    {
        $config = Config::with('media')->first();
        $item = Item::with(['subItems', 'subItems.media', 'media'])->findOrFail($id);
//        dd($item->subItems[0]->getFirstMediaUrl('images'));
        return view('front.item', compact('item', 'config'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'sub_item_id' => 'required|exists:sub_items,id',
        ]);

        // Handle the purchase logic here
        // For example, create an order or add the item to the cart

        return redirect()->route('home')->with('success', 'Purchase successful!');
    }



}
