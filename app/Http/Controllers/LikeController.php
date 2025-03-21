<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLikeRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $user = $request->user();

        $like = $post->likes()->create([
            'user_id' => $user->id,
        ]);

        return response()->json(['message' => 'Liked post'], 200);
        // return response()->json($like, 201); 
    }

    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();

        // Find the like record for the current user on the given post.
        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            return response()->json(['message' => 'Unliked successfully'], 200);
        }

        return response()->json(['message' => 'Like not found'], 404);
    }
}
