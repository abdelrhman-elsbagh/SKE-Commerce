<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessClientWallet;
use Illuminate\Support\Facades\Auth;
class BusinessClientWalletController extends Controller
{
    public function index()
    {
        $wallet = Auth::user()->wallet;
        return view('admin.client_wallets.business_client.index', compact('wallet'));
    }

    public function show($id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        return view('admin.client_wallets.business_client.show', compact('wallet'));
    }

    public function create()
    {
        return view('admin.client_wallets.business_client.create');
    }

    public function store(Request $request)
    {
        $wallet = BusinessClientWallet::create([
            'business_client_id' => Auth::id(),
            'balance' => $request->input('balance', 0.00),
        ]);

        return redirect()->route('business_client.admin.client_wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function edit($id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        return view('admin.client_wallets.business_client.edit', compact('wallet'));
    }

    public function update(Request $request, $id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        $wallet->update($request->all());

        return redirect()->route('business_client.admin.client_wallets.index')->with('success', 'Wallet updated successfully.');
    }

    public function destroy($id)
    {
        try {
            BusinessClientWallet::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Wallet deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the wallet.']);
        }
    }
}
