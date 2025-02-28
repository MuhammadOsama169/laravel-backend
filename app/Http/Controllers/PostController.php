<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate
        $attributes = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        // return 'ok';

        // $post = Post::create($attributes);

        //looking to authenticate user before creating
        $post = $request->user()->posts()->create($attributes);

        return $post;
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // return ['posts'=>$post];

        // will return this

        // "posts": {
        //     "id": 1,
        //     "title": "Hello",
        //     "body": "Long text",
        //     "created_at": "2025-02-28T12:41:13.000000Z",
        //     "updated_at": "2025-02-28T12:41:13.000000Z"
        // }

        // this will return just object
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //laravel passes users automatically so we dont need to pass it again
        //modify is described in policy to check if user.id matches post.user_id
        //prevent unauth users to update/delete posts
        Gate::authorize('modify', $post);

        //validate
        $attributes = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        // return 'ok';

        $post->update($attributes);

        return $post;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //laravel passes users automatically so we dont need to pass it again
        //modify is described in policy to check if user.id matches post.user_id
        Gate::authorize('modify', $post);


        $post->delete();

        return ['message' => "Post was deleted"];
    }
}
