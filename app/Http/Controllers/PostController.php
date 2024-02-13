<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPodcast;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index() {
        return response()->json([
            'posts' => Post::paginate(1)
        ]);
    }

    public function show($slug)
    {
        return response()->json('post', [
            'post' => Post::where('slug', '=', $slug)->first()
        ]);
    }

    public function store(Request $request)
    {
        Post::create($request->only([
            'title',
            'body',
            'slug'
        ]));

        return response()->json(["result" => "ok"], 201);
    }

    public function testQueue() {
        Post::create([
            'title' => fake()->title(),
            'body' => fake()->realText(50),
            'slug' => fake()->slug()
        ]);

        ProcessPodcast::dispatch(-6.967657818302952, 107.65900985252891);

        return response()->json([
            'message' => 'Check'
        ]);
    }
}
