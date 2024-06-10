<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::all();
        return view('admin.payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'gateway' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        $paymentMethod = PaymentMethod::create($request->all());

        if ($request->hasFile('image')) {
            $paymentMethod->addMedia($request->file('image'))->toMediaCollection('payment_method_images');
        }

        return redirect()->route('payment-methods.index')->with('success', 'Payment Method created successfully.');
    }

    public function show($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.show', compact('paymentMethod'));
    }

    public function edit($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'gateway' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image'
        ]);

        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update($request->all());

        if ($request->hasFile('image')) {
            $paymentMethod->clearMediaCollection('payment_method_images');
            $paymentMethod->addMedia($request->file('image'))->toMediaCollection('payment_method_images');
        }

        return redirect()->route('payment-methods.index')->with('success', 'Payment Method updated successfully.');
    }

    public function destroy($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->delete();
        return redirect()->route('payment-methods.index')->with('success', 'Payment Method deleted successfully.');
    }
}
