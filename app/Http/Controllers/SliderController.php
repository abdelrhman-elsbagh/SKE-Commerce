<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();
        return view('admin.sliders.index', compact('sliders'));
    }

    public function show($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.show', compact('slider'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $slider = Slider::create($request->all());

        if ($request->hasFile('image')) {
            $slider->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('sliders.index')->with('success', 'Slider created successfully.');
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->update($request->all());

        if ($request->hasFile('image')) {
            $slider->clearMediaCollection('images');
            $slider->addMedia($request->file('image'))->toMediaCollection('images');
        }

        return redirect()->route('sliders.index')->with('success', 'Slider updated successfully.');
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->delete();
        return redirect()->route('sliders.index')->with('success', 'Slider deleted successfully.');
    }
}
