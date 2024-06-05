<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        return Subscription::with('plan', 'user')->get();
    }

    public function show($id)
    {
        return Subscription::with('plan', 'user')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $subscription = Subscription::create([
            'user_id' => $request->user_id,
            'plan_id' => $request->plan_id,
            'start_date' => now(),
            'end_date' => now()->addDays(Plan::findOrFail($request->plan_id)->duration),
        ]);

        return $subscription->load('plan', 'user');
    }

    public function update(Request $request, $id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->update($request->all());
        return $subscription->load('plan', 'user');
    }

    public function destroy($id)
    {
        Subscription::findOrFail($id)->delete();
        return response()->noContent();
    }
}

