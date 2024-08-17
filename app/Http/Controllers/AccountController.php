<?php

namespace App\Http\Controllers;

use App\Exports\AccountsExport;
use App\Models\Account;
use App\Models\Client;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AccountController extends Controller
{ public function index(Request $request)
{
    $query = Account::query();

    // Apply filters based on the request
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
    }

    if ($request->filled('status')) {
        $query->where('payment_status', $request->status);
    }

    // Initialize clientIds as an empty collection
    $clientIds = collect();

    if ($request->filled('profile')) {
        $clientIds = Client::where('name', $request->profile)->pluck('id');
        $query->whereIn('client_id', $clientIds);
    }

    // Fetch accounts and calculate totals grouped by client and currency
    $accounts = $query->with(['client', 'client.currencies'])->get();

    $totals = collect(); // Initialize as an empty collection
    if ($clientIds->isNotEmpty()) {
        $totals = Account::select('client_id', 'currency_id', DB::raw('SUM(CASE WHEN payment_status = "creditor" THEN -amount WHEN payment_status = "debtor" THEN amount ELSE 0 END) as total_amount'))
            ->groupBy('client_id', 'currency_id')
            ->with(['client', 'currency'])
            ->whereIn('client_id', $clientIds) // Only for the filtered client
            ->get();
    }

    // Fetch distinct profiles for the filter dropdown
    $profiles = Client::distinct()->pluck('name');

    return view('admin.accounts.index', [
        'accounts' => $accounts,
        'profiles' => $profiles,
        'totals' => $totals, // Pass the calculated totals to the view
    ]);
}

    public function show($id)
    {
        $account = Account::with('client', 'currency')->findOrFail($id);

        // Calculate totals for the client across all currencies
        $totals = Account::select('client_id', 'currency_id', DB::raw('SUM(CASE WHEN payment_status = "creditor" THEN -amount WHEN payment_status = "debtor" THEN amount ELSE 0 END) as total_amount'))
            ->where('client_id', $account->client_id)
            ->groupBy('client_id', 'currency_id')
            ->with('client', 'currency')
            ->get();

        // Get all transactions for this client
        $transactions = Account::where('client_id', $account->client_id)->orderBy('id', 'DESC')->with('currency')->get();

        // Fetch only the currencies related to the specific client
        $currencies = $account->client->currencies;

        return view('admin.accounts.show', compact('account', 'totals', 'transactions', 'currencies'));
    }

    public function edit($id)
    {
        $account = Account::with('client.currencies')->findOrFail($id); // Load account with client's currencies
        $clients = Client::all(); // Fetch all clients
        return view('admin.accounts.edit', compact('account', 'clients'));
    }

    public function create()
    {
        $clients = Client::all(); // Fetch all clients
        return view('admin.accounts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validatedData = $request->validate([
            'payment_status' => 'required|string',
            'amount' => 'required|numeric',
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'notes' => 'string|nullable',
        ]);

        // Create the account
        $account = Account::create($validatedData);

        return redirect()->back()->with('success', 'Account created successfully.');
    }

    public function update(Request $request, $id)
    {
        // Find the account
        $account = Account::findOrFail($id);

        // Validate input
        $validatedData = $request->validate([
            'payment_status' => 'required|string',
            'amount' => 'required|numeric',
            'client_id' => 'required|exists:clients,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'notes' => 'string|nullable',
        ]);

        // Update the account
        $account->update($validatedData);

        return redirect()->route('accounts.index')->with('success', 'Account updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Account::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Account deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the account.']);
        }
    }

    public function export()
    {
        return Excel::download(new AccountsExport, 'accounts.xlsx');
    }
}
