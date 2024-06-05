<?php

namespace App\Http\Controllers;

use App\Models\DiamondRate;
use Illuminate\Http\Request;

class DiamondRateController extends Controller
{
    public function index()
    {
        return DiamondRate::all();
    }

    public function show($id)
    {
        return DiamondRate::findOrFail($id);
    }

    public function store(Request $request)
    {
        return DiamondRate::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $rate = DiamondRate::findOrFail($id);
        $rate->update($request->all());
        return $rate;
    }

    public function destroy($id)
    {
        DiamondRate::findOrFail($id)->delete();
        return response()->noContent();
    }
}

