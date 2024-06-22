<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    public function request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'payment_method_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        try {
            $purchaseRequest = new PurchaseRequest();
            $purchaseRequest->user_id = auth()->id();
            $purchaseRequest->amount = $request->amount;
            $purchaseRequest->notes = $request->notes;
            $purchaseRequest->payment_method_id = $request->payment_method_id;

            if ($request->hasFile('image')) {
                $purchaseRequest->addMedia($request->file('image'))->toMediaCollection('purchase_documents');
            }

            $purchaseRequest->save();

            return response()->json(['success' => true, 'message' => 'Purchase request submitted successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'There was an error processing your request.'], 500);
        }
    }
}
