<?php

namespace App\Http\Controllers;

use App\Models\BusinessClient;
use App\Models\BusinessPurchaseRequest;
use Illuminate\Http\Request;

class BusinessPurchaseRequestController extends Controller
{

    public function index()
    {
        $purchaseRequests = BusinessPurchaseRequest::with('businessClient')->get();
        return view('admin.business_purchase_requests.index', compact('purchaseRequests'));
    }

    public function create()
    {
        $businessClients = BusinessClient::all();
        return view('admin.business_purchase_requests.create', compact('businessClients'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'business_client_id' => 'required|exists:business_clients,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'business_purchase_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf'
        ]);

        $purchaseRequest = BusinessPurchaseRequest::create($validatedData);

        if ($request->hasFile('business_purchase_documents')) {
            $purchaseRequest->addMedia($request->file('business_purchase_documents'))->toMediaCollection('business_purchase_documents');
        }

        return response()->json(['message' => 'Business purchase request created successfully.']);
    }

    public function edit($id)
    {
        $purchaseRequest = BusinessPurchaseRequest::findOrFail($id);
        $businessClients = BusinessClient::all();
        return view('admin.business_purchase_requests.edit', compact('purchaseRequest', 'businessClients'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'business_client_id' => 'required|exists:business_clients,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'notes' => 'nullable|string',
            'business_purchase_documents' => 'nullable|file|mimes:jpg,jpeg,png,pdf'
        ]);

        $purchaseRequest = BusinessPurchaseRequest::findOrFail($id);
        $purchaseRequest->update($validatedData);

        if ($request->hasFile('business_purchase_documents')) {
            $purchaseRequest->clearMediaCollection('business_purchase_documents');
            $purchaseRequest->addMedia($request->file('business_purchase_documents'))->toMediaCollection('business_purchase_documents');
        }

        return response()->json(['message' => 'Business purchase request updated successfully.']);
    }

    public function show($id)
    {
        $purchaseRequest = BusinessPurchaseRequest::with('businessClient')->findOrFail($id);
        return view('admin.business_purchase_requests.show', compact('purchaseRequest'));
    }

    public function destroy($id)
    {
        $purchaseRequest = BusinessPurchaseRequest::findOrFail($id);
        $purchaseRequest->delete();

        return response()->json(['message' => 'Business purchase request deleted successfully.']);
    }
}
