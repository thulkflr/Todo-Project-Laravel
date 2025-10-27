<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $userPosts= User::with('posts','profile')->get();

    return view('posts.index',compact('userPosts'));
    }

    public function show($id){
        $user= User::with(['posts','profile'])->findOrFail($id);
        return view('posts.show',compact('user'));
    }
    public function create(Request $request){
        return view('posts.create');
    }
    public function store(Request $request){
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $request->user()->posts()->create([
            'title'=>$request->input('title'),
            'body'=>$request->input('body'),
        ]);
        return redirect('/posts')->with('success', 'Post created');
    }
}
