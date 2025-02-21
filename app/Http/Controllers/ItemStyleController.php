<?php

namespace App\Http\Controllers;

use App\Models\ItemStyle;
use Illuminate\Http\Request;

class ItemStyleController extends Controller
{
    public function edit()
    {
        $itemStyle = ItemStyle::first();
        return view('admin.item_styles.edit', compact('itemStyle'));
    }

    public function update(Request $request, $id)
    {
        $itemStyle = ItemStyle::findOrFail($id);
        $itemStyle->update($request->all());

        return redirect()->route('item_styles.edit', $id)->with('success', 'Item Style updated successfully.');
    }
}
