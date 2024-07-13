<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();

        return view('admin.currencies.index', compact('currencies'));
    }

    public function show($id)
    {
        $currency = Currency::findOrFail($id);

        return view('admin.currencies.show', compact('currency'));
    }

    public function create()
    {
        return view('admin.currencies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Currency::create($request->all());

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function edit($id)
    {
        $currency = Currency::findOrFail($id);

        return view('admin.currencies.edit', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'currency' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update($request->all());

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Currency::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Currency deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the currency.']);
        }
    }
}
