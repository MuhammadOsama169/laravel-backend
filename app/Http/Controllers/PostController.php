<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Notifications\NewPostNotification;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Uploadcare\Configuration;
use Uploadcare\Uploadcare\Client;

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
        // return Post::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        // Get all validated data from the request
        $data = $request->validated();

        // Check if an avatar file was uploaded
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');

            // Upload the file to Cloudinary without transformation options
            $uploadResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'public'
            ]);

            // Retrieve the secure URL for the uploaded file and add it to the data array
            $data['avatar'] = $uploadResponse->getSecurePath();
            $data['avatar_public_id'] = $uploadResponse->getPublicId();
        }

        // Create the post using the authenticated user with the combined data
        $post = $request->user()->posts()->create($data);

        // Notify admin that a new post is created (if an admin exists)
        event(new \App\Events\PostCreated($post));

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
    public function update(UpdatePostRequest $request, Post $post)
    {
        //laravel passes users automatically so we dont need to pass it again
        //modify is described in policy to check if user.id matches post.user_id
        //prevent unauth users to update/delete posts
        Gate::authorize('modify', $post);

        // return 'ok';

        $post->update($request->validated());

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
        
        // If the post has a Cloudinary public ID, delete the associated asset
        if ($post->avatar_public_id) {
            Cloudinary::destroy($post->avatar_public_id);
        }

        $post->delete();

        return ['message' => "Post was deleted"];
    }
}
