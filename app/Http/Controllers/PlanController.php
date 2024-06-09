<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Feature;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
        ]);

        $plan = Plan::create($request->only(['name', 'price', 'duration', 'description']));

        if ($request->has('features')) {
            foreach ($request->features as $featureData) {
                $feature = Feature::create($featureData);
                $plan->features()->attach($feature->id);
            }
        }

        return redirect()->route('plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit($id)
    {
        $plan = Plan::with('features')->findOrFail($id);
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'duration' => 'required|integer',
            'description' => 'nullable|string',
            'features' => 'nullable|array',
            'features.*.id' => 'sometimes|exists:features,id',
            'features.*.name' => 'required|string|max:255',
            'features.*.description' => 'nullable|string',
        ]);

        $plan = Plan::findOrFail($id);
        $plan->update($request->only(['name', 'price', 'duration', 'description']));

        // Sync features
        $featureIds = [];
        if ($request->has('features')) {
            foreach ($request->features as $featureData) {
                if (isset($featureData['id'])) {
                    $feature = Feature::findOrFail($featureData['id']);
                    $feature->update($featureData);
                } else {
                    $feature = Feature::create($featureData);
                }
                $featureIds[] = $feature->id;
            }
        }

        $plan->features()->sync($featureIds);

        return redirect()->route('plans.index')->with('success', 'Plan updated successfully.');
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return redirect()->route('plans.index')->with('success', 'Plan deleted successfully.');
    }
}
