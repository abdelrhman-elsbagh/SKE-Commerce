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
                'font' => 'Nunito',
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

        if ($request->hasFile('dark_logo_input')) {
            $config->clearMediaCollection('dark_logos'); // Remove any previous dark logo
            $config->addMediaFromRequest('dark_logo_input')->toMediaCollection('dark_logos');
        }

        if ($request->hasFile('fav_icon')) {
            $config->clearMediaCollection('fav_icon'); // Remove any previous dark logo
            $config->addMediaFromRequest('fav_icon')->toMediaCollection('fav_icon');
        }

        return redirect()->route('configs.edit')->with('success', 'Config updated successfully.');
    }
}
