<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with('user')->latest()->paginate(10);
        return view('comment.comment', ['comments' => $comments]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('comment.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|min:3|max:500',
            'article_id' => 'required|exists:articles,id'
        ]);
        
        $comment = new Comment();
        $comment->text = $request->text;
        $comment->article_id = $request->article_id;
        $comment->users_id = auth()->id();
        $comment->save();
        
        return redirect()->route('article.show', ['article' => $request->article_id])
            ->with('message', 'Comment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return view('comment.show', ['comment' => $comment]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        return view('comment.edit', ['comment' => $comment]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'text' => 'required|min:3|max:500'
        ]);
        
        $comment->text = $request->text;
        $comment->save();
        
        return redirect()->route('article.show', ['article' => $comment->article_id])
            ->with('message', 'Comment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        $articleId = $comment->article_id;
        $comment->delete();
        
        return redirect()->route('article.show', ['article' => $articleId])
            ->with('message', 'Comment deleted successfully');
    }
}
