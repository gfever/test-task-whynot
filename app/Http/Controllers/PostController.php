<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = resolve(Post::class);
        if (!\Auth::user()->isAdmin()) {
            $posts = $posts->where('published', '=', true);
        }

        return view('post.index', ['posts' => $posts->paginate(10), 'page' => 'main']);
    }

    public function userIndex()
    {
        $posts = \Auth::user()->posts()->paginate(10);
        return view('post.index', ['posts' => $posts, 'page' => 'user']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail|required|max:255',
            'body' => 'required',
        ]);

        $data = $request->toArray();
        $data['user_id'] = \Auth::id();
        resolve(Post::class)->create($data);
        return redirect()->action('PostController@userIndex');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return view('post.show', ['post' => $post]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function edit(Post $post)
    {
        $this->authorize('update', $post);
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'bail|string|max:255',
            'body' => 'string'
        ]);

        $post->update($request->except('user_id'));
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);
        $post->delete();

        return back();
    }

    /**
     * @param Post $post
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function publishToggle(Post $post)
    {
        $this->authorize('publish', $post);

        $post->published = !$post->published;
        $post->save();

        return back();
    }
}
