<?php

namespace App\Http\Controllers;

use App\Models\TermsConditions;
use Illuminate\Http\Request;

class TermsController extends Controller
{
    public function edit()
    {
        $termsConditions = TermsConditions::first();

        if (!$termsConditions) {
            $termsConditions = TermsConditions::create([
                'data' => 'Default Terms and Conditions'
            ]);
        }

        return view('admin.terms.edit', compact('termsConditions'));
    }

    public function update(Request $request, $id)
    {
        $termsConditions = TermsConditions::findOrFail($id);
        $termsConditions->update($request->all());

        return redirect()->route('terms.edit')->with('success', 'Terms and Conditions updated successfully.');
    }
}
