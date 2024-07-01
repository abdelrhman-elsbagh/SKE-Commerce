<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function edit()
    {

        $news = News::first();
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'news' => 'required'
        ]);

        $news = News::findOrFail($id);
        $news->update($request->all());

        return redirect()->route('news.edit', $news->id)->with('success', 'News updated successfully');
    }
}
