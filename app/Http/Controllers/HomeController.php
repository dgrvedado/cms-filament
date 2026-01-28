<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $posts = Post::all();
        return view('home', compact('posts'));
    }

    public function post($slug): View
    {
        $post = Post::where('slug', $slug)->first();
        return view('post', compact('post'));
    }
}
