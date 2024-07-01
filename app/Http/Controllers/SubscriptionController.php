<?php

namespace App\Http\Controllers;

use App\Models\BusinessClient;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::all();
        return view('admin.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $businessClients = BusinessClient::all();
        $plans = Plan::all();
        return view('admin.subscriptions.create', compact('businessClients', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'business_client_id' => 'required',
            'plan_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
        ]);

        Subscription::create($request->all());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription created successfully.');
    }

    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $businessClients = BusinessClient::all();
        $plans = Plan::all();
        return view('admin.subscriptions.edit', compact('subscription', 'businessClients', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $request->validate([
            'business_client_id' => 'required',
            'plan_id' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required|string',
        ]);

        $subscription->update($request->all());

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription updated successfully.');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('subscriptions.index')
            ->with('success', 'Subscription deleted successfully.');
    }
}

