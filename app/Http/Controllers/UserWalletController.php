<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{
    public function index()
    {
        $wallets = UserWallet::with('user')->get();

        foreach ($wallets as $wallet) {
            $wallet->activeOrdersCount = Order::where('user_id', $wallet->user->id)
                ->where('status', 'active')
                ->count();

            $wallet->refundedOrdersCount = Order::where('user_id', $wallet->user->id)
                ->where('status', 'refunded')
                ->count();
        }

        return view('admin.user_wallet.index', compact('wallets'));
    }


    public function show($id)
    {
        $wallet = UserWallet::findOrFail($id);
        return view('admin.user_wallet.show', compact('wallet'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.user_wallet.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'balance' => 'required|numeric',
        ]);

        $wallet = UserWallet::create([
            'user_id' => $request->user_id,
            'balance' => $request->balance,
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Wallet created successfully.']);
        }

        return redirect()->route('user-wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function edit($id)
    {
        $wallet = UserWallet::findOrFail($id);
        $users = User::all();
        return view('admin.user_wallet.edit', compact('wallet', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'balance' => 'required|numeric',
        ]);

        $wallet = UserWallet::findOrFail($id);
        $oldBalance = $wallet->balance;
        $newBalance = $request->balance;

        // Determine if the balance has increased
        $increaseStatus = $newBalance > $oldBalance ? 'increased' : 'decrease';

        // Update the wallet
        $wallet->balance = $newBalance;
        $wallet->increase_status = $increaseStatus;
        $wallet->save();

        if ($request->ajax()) {
            return response()->json(['status' => 'success', 'message' => 'Wallet updated successfully.']);
        }

        return redirect()->route('user-wallets.index')->with('success', 'Wallet updated successfully.');
    }


    public function destroy($id)
    {
        try {
            UserWallet::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Wallet deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the wallet.']);
        }
    }
}
