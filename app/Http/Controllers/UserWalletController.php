<?php

namespace App\Http\Controllers;

use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserWalletController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        return view('admin.wallets.user.index', compact('wallet'));
    }

    public function show($id)
    {
        $wallet = UserWallet::findOrFail($id);
        return view('admin.wallets.user.show', compact('wallet'));
    }

    public function create()
    {
        return view('admin.wallets.user.create');
    }

    public function store(Request $request)
    {
        $wallet = UserWallet::create([
            'user_id' => Auth::id(),
            'balance' => $request->input('balance', 0.00),
        ]);

        return redirect()->route('user.admin.wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function edit($id)
    {
        $wallet = UserWallet::findOrFail($id);
        return view('admin.wallets.user.edit', compact('wallet'));
    }

    public function update(Request $request, $id)
    {
        $wallet = UserWallet::findOrFail($id);
        $wallet->update($request->all());

        return redirect()->route('user.admin.wallets.index')->with('success', 'Wallet updated successfully.');
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
