<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('media')
            ->withCount('likes')
            ->withCount('dislikes')
            ->get();

        return view('admin.posts.index', compact('posts'));
    }


    public function show($id)
    {
        $post = Post::with('media')
            ->withCount('likes')
            ->withCount('dislikes')
            ->findOrFail($id);

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

    // PostController.php

    public function likePost($id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        // Check if the user has already liked the post
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            // If the user has already liked the post, remove the like
            $like->delete();
            $liked = false;
        } else {
            // If the user has disliked the post, remove the dislike
            $post->dislikes()->where('user_id', $user->id)->delete();

            // Add the like
            $post->likes()->create(['user_id' => $user->id]);
            $liked = true;
        }

        // Get updated counts
        $likesCount = $post->likes()->count();
        $dislikesCount = $post->dislikes()->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);
    }

    public function dislikePost($id)
    {
        $user = Auth::user();
        $post = Post::findOrFail($id);

        // Check if the user has already disliked the post
        $dislike = $post->dislikes()->where('user_id', $user->id)->first();

        if ($dislike) {
            // If the user has already disliked the post, remove the dislike
            $dislike->delete();
            $disliked = false;
        } else {
            // If the user has liked the post, remove the like
            $post->likes()->where('user_id', $user->id)->delete();

            // Add the dislike
            $post->dislikes()->create(['user_id' => $user->id]);
            $disliked = true;
        }

        // Get updated counts
        $likesCount = $post->likes()->count();
        $dislikesCount = $post->dislikes()->count();

        return response()->json([
            'success' => true,
            'disliked' => $disliked,
            'likes_count' => $likesCount,
            'dislikes_count' => $dislikesCount,
        ]);
    }

}
