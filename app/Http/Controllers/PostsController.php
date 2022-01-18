<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function create()
     {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $data = request()->validate([
            'caption' => 'required',
            'image' => 'required',
            // 'image' => ['required', 'image'],
        ]);

       $image_path = request('image')->store('uploads', 'public');

       $image = Image::make(public_path("storage/{$image_path}"))->fit(1080, 1080);
       $image->save();

        auth()->user()->posts()->create([
            'caption' => $data['caption'],
            'image' => $image_path,
        ]);

        return redirect('/profile/' . auth()->user()->id);
    }

    public function show(\App\Post $post)
    {
       return view('posts.show', compact('post'));
    }
}
