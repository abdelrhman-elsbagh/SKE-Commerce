<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function edit()
    {
        $config = Config::first();

        if (!$config) {
            $config = Config::create([
                'name' => 'Default Name',
                'description' => 'Default Description',
                'whatsapp' => 'Default WhatsApp',
                'telegram' => 'Default Telegram',
                'phone' => 'Default Phone',
                'facebook' => 'Default Facebook',
                'fee' => 10,
                'discount' => 0,
                'currency' => 'USD',
            ]);
        }

        return view('admin.configs.edit', compact('config'));
    }

    public function update(Request $request, $id)
    {
        $config = Config::findOrFail($id);
        $config->update($request->all());

        if ($request->hasFile('logo')) {
            $config->clearMediaCollection('logos');
            $config->addMedia($request->file('logo'))->toMediaCollection('logos');
        }

        return redirect()->route('configs.edit')->with('success', 'Config updated successfully.');
    }
}
