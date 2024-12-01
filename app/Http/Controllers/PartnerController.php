<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index(Request $request)
    {
        $query = Partner::orderBy('created_at', 'DESC');

        if ($request->has('name') && $request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $partners = $query->get();

        return view('admin.partners.index', compact('partners'))->with([
            'nameFilter' => $request->name,
        ]);
    }

    public function create()
    {
        return view('admin.partners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'ar_name' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'insta' => 'nullable|string',
            'telegram' => 'nullable|string',
            'partner_image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $partner = Partner::create([
            'name' => $request->name,
            'description' => $request->description,
            'ar_name' => $request->ar_name,
            'ar_description' => $request->ar_description,
            'facebook' => $request->facebook,
            'whatsapp' => $request->whatsapp,
            'insta' => $request->insta,
            'telegram' => $request->telegram,
        ]);

        if ($request->hasFile('partner_image')) {
            $partner->addMedia($request->file('partner_image'))->toMediaCollection('partner_images');
        }

        return redirect()->route('partners.index')->with('success', 'Agent created successfully.');
    }

    public function show($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.show', compact('partner'));
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        return view('admin.partners.edit', compact('partner'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string',
            'description' => 'nullable|string',
            'ar_name' => 'nullable|string',
            'ar_description' => 'nullable|string',
            'facebook' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'insta' => 'nullable|string',
            'telegram' => 'nullable|string',
            'partner_image' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $partner = Partner::findOrFail($id);

        $partner->update([
            'name' => $request->name,
            'description' => $request->description,
            'ar_name' => $request->ar_name,
            'ar_description' => $request->ar_description,
            'facebook' => $request->facebook,
            'whatsapp' => $request->whatsapp,
            'insta' => $request->insta,
            'telegram' => $request->telegram,
        ]);

        if ($request->hasFile('partner_image')) {
            $partner->clearMediaCollection('partner_images');
            $partner->addMedia($request->file('partner_image'))->toMediaCollection('partner_images');
        }

        $partner->save();

        return redirect()->route('partners.index')->with('success', 'Agent updated successfully.');
    }

    public function destroy($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();
        return redirect()->route('partners.index')->with('success', 'Agent deleted successfully.');
    }
}
