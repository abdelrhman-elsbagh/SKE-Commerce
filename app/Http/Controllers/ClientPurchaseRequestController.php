<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientPurchaseRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseRequest::with('user', 'paymentMethod')->orderBy('created_at', 'DESC');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->start_date;
            $endDate = $request->end_date;
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $purchaseRequests = $query->get();

        return view('admin.purchase_requests.index', compact('purchaseRequests'))->with([
            'statusFilter' => $request->status,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date
        ]);
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

        // Check if the purchase request has already been updated
        if ($purchaseRequest->updated_at != $purchaseRequest->created_at) {
            return response()->json(['error' => 'Purchase request has already been updated and cannot be updated again.'], 400);
        }

        $oldStatus = $purchaseRequest->status;

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

        // Check if the old status was not "Approved" and the new status is "Approved"
        if ($oldStatus !== 'approved' && $request->status === 'approved') {
            $wallet = UserWallet::where('user_id', $request->user_id)->first();
            if ($wallet) {
                $wallet->balance += $request->amount;
                $wallet->save();
            } else {
                // Create a new wallet entry if it doesn't exist
                UserWallet::create([
                    'user_id' => $request->user_id,
                    'balance' => $request->amount,
                ]);
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Purchase request updated successfully.']);
    }



    public function destroy($id)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($id);
        $purchaseRequest->delete();
        return redirect()->route('purchase-requests.index')->with('success', 'Purchase request deleted successfully.');
    }
}
