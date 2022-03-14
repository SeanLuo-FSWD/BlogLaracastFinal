<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Validation\Rule;

class AdminPostController extends Controller
{
    public function index()
    {
        return view('admin.posts.index', [
           'posts' => Post::paginate(50)
        ]);
    }

    public function create()
    {
        //@@ auth validation was done here, but got moved to middleware MustBeAdministrator
        return view('admin.posts.create');
    }

    public function store()
    {
        Post::create(array_merge($this->validatePost(), [
            'user_id' => request()->user()->id,
            'thumbnail' => request()->file('thumbnail')->store('thumbnails')
        ]));

        return redirect('/');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', ['post' => $post]);
    }

    public function update(Post $post)
    {
        $attributes = $this->validatePost($post);

        if ($attributes['thumbnail'] ?? false) {
            $attributes['thumbnail'] = request()->file('thumbnail')->store('thumbnails');
        }

        $post->update($attributes);

        return back()->with('success', 'Post Updated!');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return back()->with('success', 'Post Deleted!');
    }

    protected function validatePost(?Post $post = null): array
    {
        $post ??= new Post(); //@@68 If $post value is passed in, use it, otherwise create a new one

        return request()->validate([
            'title' => 'required',
            //@@68 Change validation depending on if post exist
            'thumbnail' => $post->exists ? ['image'] :['required', 'image'],
            'slug' => ['required', Rule::unique('posts', 'slug')->ignore($post)], //@@67 must ignore so avoid unique constraint error
            'excerpt' => 'required',
            'body' => 'required',
            //@@63 Must exist in category table's id column
            'category_id' => ['required', Rule::exists('categories', 'id')]
        ]);
    }
}
