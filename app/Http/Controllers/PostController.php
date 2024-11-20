<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function createPost(Request $request) {
        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required']);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->guard()->id();

        Post::create($incomingFields);
        return redirect('/');
    }

    public function showEditScreen(Post $post) {

        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }

        return view('edit-post', ['post'=> $post]);
    }

    public function actuallyUpdatePost(Post $post, Request $request)
    {
        if (auth()->user()->id !== $post['user_id']) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'title'=>'required',
            'body'=>'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);

        $post->update($incomingFields);
        return redirect('/');
    }

    public function deletePost (Post $post){
        if (auth()->user()->id === $post['user_id']) {
            $post->delete();

        }
        return redirect('/');
    }
}