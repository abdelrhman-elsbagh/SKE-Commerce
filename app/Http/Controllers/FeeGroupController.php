<?php

namespace App\Http\Controllers;

use App\Models\FeeGroup;
use Illuminate\Http\Request;

class FeeGroupController extends Controller
{
    public function index()
    {
        $feeGroups = FeeGroup::all();
        return view('admin.fee_groups.index', compact('feeGroups'));
    }

    public function show($id)
    {
        $feeGroup = FeeGroup::findOrFail($id);
        return view('admin.fee_groups.show', compact('feeGroup'));
    }

    public function create()
    {
        return view('admin.fee_groups.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fee' => 'required|numeric',
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $feeGroup = FeeGroup::create($request->only('fee', 'name'));

        if ($request->hasFile('image')) {
            $feeGroup->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->hasFile('logo')) {
            $feeGroup->addMedia($request->file('logo'))->toMediaCollection('logos');
        }

        return redirect()->route('fee_groups.index')->with('success', 'Fee Group created successfully.');
    }

    public function edit($id)
    {
        $feeGroup = FeeGroup::findOrFail($id);
        return view('admin.fee_groups.create', compact('feeGroup'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fee' => 'required|numeric',
            'name' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $feeGroup = FeeGroup::findOrFail($id);

        // Prevent updating the "name" if the Fee Group is "Default"
        /*if (strtolower($feeGroup->name) == 'default' && strtolower($request->input('name'))  != strtolower($feeGroup->name) ) {
            return redirect()->route('fee_groups.index')->with('error', 'The name of the "Default" Fee Group cannot be changed.');
        }*/

        $feeGroup->update($request->only('fee', 'name'));

        if ($request->hasFile('image')) {
            $feeGroup->clearMediaCollection('images');
            $feeGroup->addMedia($request->file('image'))->toMediaCollection('images');
        }

        if ($request->hasFile('logo')) {
            $feeGroup->clearMediaCollection('logos');
            $feeGroup->addMedia($request->file('logo'))->toMediaCollection('logos');
        }

        return redirect()->route('fee_groups.index')->with('success', 'Fee Group updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $feeGroup = FeeGroup::findOrFail($id);

            // Prevent deletion if the fee group's name is "Default"
            if ($feeGroup->name === 'Default') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The "Default" Fee Group cannot be deleted.'
                ], 403); // HTTP 403 Forbidden
            }

            $feeGroup->delete();
            return response()->json(['status' => 'success', 'message' => 'Fee Group deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the Fee Group.'
            ], 500); // HTTP 500 Internal Server Error
        }
    }
}
