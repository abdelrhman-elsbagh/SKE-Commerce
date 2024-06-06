<?php

namespace App\Http\Controllers;
use App\Models\Item;
use App\Models\Slider;
use Illuminate\Http\Request;


class HomeController extends Controller
{
    public function index(Request $request)
    {
        $sliders = Slider::with('media')->get();

        // Retrieve all items with their associated categories and media
        $items = Item::with('category', 'media')->get();

        // Organize items by category
        $categorizedItems = $items->groupBy(function($item) {
            return $item->category->name;
        });

        // Pass the categorized items with media to the view
        return view('front.index', ['categorizedItems' => $categorizedItems, 'sliders' => $sliders]);
    }

}
