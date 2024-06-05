<?php

namespace App\Http\Controllers;

use App\Models\DiamondRate;
use Illuminate\Http\Request;

class DiamondRatesController extends Controller
{
    public function index()
    {
        return view('admin.diamond_rates.index', ['diamondRates' => DiamondRate::all()]);
    }

    public function show($id)
    {
        return view('admin.diamond_rates.show', ['diamondRate' => DiamondRate::findOrFail($id)]);
    }

    public function create()
    {
        return view('admin.diamond_rates.create');
    }

    public function store(Request $request)
    {
        $diamondRate = DiamondRate::create($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => 'Diamond rate created successfully.']);
        }

        return redirect()->route('diamond_rates.index')->with('success', 'Diamond rate created successfully.');
    }

    public function edit($id)
    {
        return view('admin.diamond_rates.edit', ['diamondRate' => DiamondRate::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $diamondRate = DiamondRate::findOrFail($id);
        $diamondRate->update($request->all());

        if ($request->ajax()) {
            return response()->json(['success' => 'Diamond rate updated successfully.']);
        }

        return redirect()->route('diamond_rates.index')->with('success', 'Diamond rate updated successfully.');
    }

    public function destroy($id)
    {
        try {
            DiamondRate::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Diamond rate deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the diamond rate.']);
        }
    }
}
