<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Events\NewArticleEvent;
use App\Notifications\NewArticleNotify;


class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 0;
        $articles = Cache::remember('articles_' . $page, 300, function () {
            return Article::latest()->paginate(5);
        });
        return response()->json($articles);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Article::class);
        return response()->json(['message' => 'Create form data']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'articles_*[0-9]'])->get();
        foreach ($keys as $param) {
            Cache::forget($param->key);
        }
        Gate::authorize('create', Article::class);
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);
        $article = new Article;
        $article->date_public = $request->date;
        $article->title = request('title');
        $article->text = $request->text;
        $article->users_id = auth()->id();
        if ($article->save()) {
            NewArticleEvent::dispatch($article);

            // Отправляем уведомления читателям (всем пользователям, кроме автора статьи)
            $readers = User::where('id', '!=', auth()->id())->get();
            Notification::send($readers, new NewArticleNotify($article->title, $article->id));
        }
        return response()->json(['message' => 'Create successful', 'article' => $article], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        if (isset($_GET['notify']) && auth()->check()) {
            $notification = auth()->user()->notifications->where('id', $_GET['notify'])->first();
            if ($notification) {
                $notification->markAsRead();
            }
        }
        $comments = Cache::rememberForever('comments' . $article->id, function () use ($article) {
            return Comment::where('article_id', $article->id)
                ->where('accept', true)
                ->get();
        });
        return response()->json([
            'article' => $article,
            'comments' => $comments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('restore', $article);
        return response()->json(['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);
        $request->validate([
            'date' => 'required|date',
            'title' => 'required|min:10',
            'text' => 'max:100'
        ]);
        $article->date_public = $request->date;
        $article->title = request('title');
        $article->text = $request->text;
        $article->users_id = 1;
        if ($article->save()) {
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'articles_*[0-9]'])->get();
            foreach ($keys as $param) {
                Cache::forget($param->key);
            }
        }
        return response()->json(['message' => 'Update successful', 'article' => $article]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);
        if ($article->delete()) {
            Cache::forget('comments' . $article->id);
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'articles_*[0-9]'])->get();
            foreach ($keys as $param) {
                Cache::forget($param->key);
            }
        }
        return response()->json(['message' => 'Delete successful']);
    }
}
