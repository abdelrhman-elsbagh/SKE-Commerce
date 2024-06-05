<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        return Plan::all();
    }

    public function show($id)
    {
        return Plan::findOrFail($id);
    }

    public function store(Request $request)
    {
        return Plan::create($request->all());
    }

    public function update(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        $plan->update($request->all());
        return $plan;
    }

    public function destroy($id)
    {
        Plan::findOrFail($id)->delete();
        return response()->noContent();
    }
}

