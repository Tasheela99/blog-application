<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'body' => $request->body,
            'status' => $request->status,
        ]);

        return response()->json($post, 201);
    }

    public function index()
    {
        $posts = Post::where('user_id', Auth::id())->get();
        return response()->json($posts, 200);
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        $post->update($request->only(['title', 'body', 'status']));

        return response()->json($post, 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully'], 204);
    }

    public function getAllPublished(Request $request)
    {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'size' => 'nullable|integer|min:1|max:100',
        ]);

        $page = $request->input('page', 1);
        $size = $request->input('size', 10);

        $posts = Post::with(['user:id,name', 'comments'])
            ->where('status', 'published')
            ->paginate($size, ['*'], 'page', $page);

        return response()->json($posts, 200);
    }

    public function getPostsFiltered(Request $request)
    {
        try {
            $validated = $request->validate([
                'page' => 'nullable|integer|min:0',
                'size' => 'nullable|integer|min:1|max:100',
                'search' => 'nullable|string|max:255',
                'status' => 'nullable|in:published,draft'
            ]);

            $page = $validated['page'] ?? 1;
            $size = $validated['size'] ?? 15;
            $search = $validated['search'] ?? '';
            $status = $validated['status'] ?? null;

            $query = Post::with(['user:id,name', 'comments' => function($query) {
                $query->latest()->take(5);
            }]);

            if ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            }

            if ($status) {
                $query->where('status', $status);
            }

            $posts = $query->paginate($size, ['*'], 'page', $page);

            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching posts: ' . $e->getMessage()], 500);
        }
    }
}
