<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        return view('posts.index', [
            //@@38_03:55_query builders to search. Created as scopeFilter("scope" + name of method) in Post.php model.
            'posts' => Post::latest()->filter(
                        //@@38_07:35 array passed as "$filters" to scopeFilter
                        request(['search', 'category', 'author'])
                    )->paginate(18)->withQueryString() //@@44 "withQueryString" to preserve category query upon page change
            //@@44 changed from ->get() to ->paginate()
        ]);
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }
}
