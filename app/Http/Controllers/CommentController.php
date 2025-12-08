<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Support\Facades\Gate;
use App\Jobs\VeryLongJob;


class CommentController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'moderator') {
            abort(403, 'Access denied. Only moderator can moderate comments.');
        }
        
        $comments = Comment::where(function($query) {
                $query->where('accept', false)
                      ->orWhereNull('accept');
            })
            ->latest()
            ->paginate(10);
        return view('comment.index', ['comments' => $comments]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'text' => 'min:10|required',
        ]);

        $article = Article::FindOrFail($request->article_id);
        $comment = new Comment;
        $comment->text = $request->text;
        $comment->article_id = $request->article_id;
        $comment->users_id = auth()->id();
        $comment->accept = false;
        if ($comment->save())
            VeryLongJob::dispatch($article, $comment, auth()->user()->name);
        return redirect()->route('article.show', $request->article_id)->with('message', "Comment add succesful and enter for moderation");
    }

    public function edit(Comment $comment)
    {
        Gate::authorize('comment', $comment);
        return view('comment.edit', ['comment' => $comment]);
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('comment', $comment);

        $request->validate([
            'text' => 'min:10|required',
        ]);

        $comment->text = $request->text;
        $comment->save();

        return redirect()->route('article.show', $comment->article_id)->with('message', 'Comment updated successfully');
    }

    public function delete(Comment $comment)
    {
        Gate::authorize('comment', $comment);
        $articleId = $comment->article_id;
        $comment->delete();

        return redirect()->route('article.show', $articleId)->with('message', 'Comment deleted successfully');
    }

    public function accept(Comment $comment)
    {
        if (auth()->user()->role !== 'moderator') {
            abort(403, 'Access denied. Only moderator can moderate comments.');
        }
        
        $comment->accept = true;
        $comment->save();
        return redirect()->route('comment.index')->with('message', 'Comment accepted');
    }

    public function reject(Comment $comment)
    {
        if (auth()->user()->role !== 'moderator') {
            abort(403, 'Access denied. Only moderator can moderate comments.');
        }
        
        $comment->delete();
        return redirect()->route('comment.index')->with('message', 'Comment rejected');
    }
}