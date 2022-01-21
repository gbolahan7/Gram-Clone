@extends('layouts.app')

@section('content')
<div class="container">
<div class="row">
    <div class="col-3 pl-5">
        <img src ="{{$user->profile->profileImage()}}" style= "height: 150px" alt="default" class="rounded-circle ">
    </div>
        <div class="col-9">
            <div class="d-flex justify-content-between align-items-baseline">
              <div class="d-flex align-items-center pb-3">
                    <div class ="h4">{{$user-> username}}</div>

                  <follow-button user-id="{{$user->id}}" follows="{{$follows}}"></follow-button>
             </div>
                <a href="/post/create">Add New Post</a>
            </div>
            @can ('update', $user->profile)
            <a href="/profile/{{ $user-> id }}/edit">Edit Profile</a>
            @endcan
        <div class="d-flex">
            <div class= "pr-4"><strong>{{$postCount}}</strong> Posts</div>
            <div class= "pr-4" ><strong>{{$followersCount}}</strong> Followers</div>
            <div class= "pr-4"><strong>{{$followingCount}}</strong> Following</div>
        </div>
        <div class="pt-3 font-weight-bold">{{$user->profile->title}}</div>
        <div>{{$user->profile->description}}</div>
        <div>{{$user->profile->url}}</div>
    </div>
 </div>

    <div class="row pt-4">
        @foreach($user->posts as $post)
        <div class="col-4">
            <a href="/post/{{$post->id}}">
            <img src="/storage/{{$post->image}}" alt="" class="w-100">
            </a>
        </div>
       @endforeach
    </div>
</div>
@endsection
