<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('media')->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with('media')->findOrFail($id);

        return view('admin.posts.show', compact('post'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Create the main post
        $post = Post::create($request->all());

        // Handle the main post image if provided
        if ($request->hasFile('image')) {
            $post->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // Redirect to the posts index with a success message
        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function edit($id)
    {
        $post = Post::with('media')->findOrFail($id);

        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, $id)
    {
        // Validate request
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Find and update the post
        $post = Post::findOrFail($id);
        $post->update($request->all());

        // Handle the post image if provided
        if ($request->hasFile('image')) {
            $post->clearMediaCollection('images');
            $post->addMedia($request->file('image'))->toMediaCollection('images');
        }

        // Redirect to the posts index with a success message
        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy($id)
    {
        try {
            Post::findOrFail($id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Post deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the post.']);
        }
    }
}
