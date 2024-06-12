<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientPurchaseRequestController extends Controller
{
    public function index()
    {
        $purchaseRequests = PurchaseRequest::with('user', 'paymentMethod')->get();
        return view('admin.purchase_requests.index', compact('purchaseRequests'));
    }

    public function create()
    {
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        return view('admin.purchase_requests.create', compact('users', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'document' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $purchaseRequest = PurchaseRequest::create([
            'user_id' => $request->user_id,
            'notes' => $request->notes,
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_method_id' => $request->payment_method_id,
        ]);

        if ($request->hasFile('document')) {
            $purchaseRequest->addMedia($request->file('document'))->toMediaCollection('purchase_documents');
        }

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request created successfully.');
    }

    public function show($id)
    {
        $purchaseRequest = PurchaseRequest::with('user', 'paymentMethod')->findOrFail($id);
        return view('admin.purchase_requests.show', compact('purchaseRequest'));
    }

    public function edit($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $users = User::all();
        $paymentMethods = PaymentMethod::all();
        return view('admin.purchase_requests.edit', compact('purchaseRequest', 'users', 'paymentMethods'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'status' => 'required|string',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'document' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->update([
            'user_id' => $request->user_id,
            'notes' => $request->notes,
            'amount' => $request->amount,
            'status' => $request->status,
            'payment_method_id' => $request->payment_method_id,
        ]);

        if ($request->hasFile('document')) {
            $purchaseRequest->clearMediaCollection('purchase_documents');
            $purchaseRequest->addMedia($request->file('document'))->toMediaCollection('purchase_documents');
        }

        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request updated successfully.');
    }

    public function destroy($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request deleted successfully.');
    }
}
