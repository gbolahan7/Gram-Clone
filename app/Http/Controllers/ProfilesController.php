<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class ProfilesController extends Controller
{
    //
    public function index(User $user)
    {
    //    $user = User::findOrFail($user);
    $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;
    
    $postCount = Cache::remember(
                'count.posts.' . $user->id, //key used for the cache
                 now()->addSeconds(30), // store it for 30sec
                function() use ($user){
                    return $user->posts->count();
                });

    $followersCount = Cache::remember(
                    'count.followers.' . $user->id, //key used for the cache
                    now()->addSeconds(30), // store it for 30sec
                    function() use ($user){
                        return $user->profile->followers->count();
               });

    $followingCount = Cache::remember(
                    'count.following.' . $user->id, //key used for the cache
                    now()->addSeconds(30), // store it for 30sec
                    function() use ($user){
                        return $user->following->count();
                });

        
        return view('profiles.index', compact('user', 'follows', 'postCount', 'followersCount', 'followingCount'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user->profile);
        return view('profiles.edit', compact('user'));
    }

    public function update(User $user)
    {
        $this->authorize('update', $user->profile);

        $data = request()->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'url',
            'image' => '',
        ]);


        if (request('image')) {
            
        $image_path = request('image')->store('profile', 'public');

        $image = Image::make(public_path("storage/{$image_path}"))->fit(1080, 1080);
        $image->save();

        $imageArray = ['image' => $image_path];
        }

        auth()->user()->profile->update(array_merge(
            $data,
            $imageArray ?? []
        )); //grabbing only the authenticated user

        return redirect("/profile/{$user->id}");


    }
}
