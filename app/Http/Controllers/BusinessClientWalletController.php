<?php

namespace App\Http\Controllers;

use App\Models\BusinessClient;
use Illuminate\Http\Request;
use App\Models\BusinessClientWallet;
use Illuminate\Support\Facades\Auth;

class BusinessClientWalletController extends Controller
{
    public function index()
    {
        $wallets = BusinessClientWallet::with('businessClient')->get();
        return view('admin.client_wallets.index', compact('wallets'));
    }

    public function show($id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        return view('admin.client_wallets.show', compact('wallet'));
    }

    public function create()
    {
        $businessClients = BusinessClient::all();
        return view('admin.client_wallets.create', compact('businessClients'));
    }


    public function store(Request $request)
    {
        $wallet = BusinessClientWallet::create([
            'business_client_id' =>  $request->input('business_client_id'),
            'balance' => $request->input('balance', 0.00),
        ]);

        return redirect()->route('business-client-wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function edit($id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        $businessClients = BusinessClient::all();
        return view('admin.client_wallets.edit', compact('wallet', 'businessClients'));
    }

    public function update(Request $request, $id)
    {
        $wallet = BusinessClientWallet::findOrFail($id);
        $wallet->update($request->all());

        return redirect()->route('business-client-wallets.index')->with('success', 'Wallet updated successfully.');
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
