<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Click;
use App\Models\Article;
use Illuminate\Support\Facades\Log;

class StatMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        preg_match('/\d+/', $request->path(), $matches);
        $article = Article::findOrFail($matches[0]);
        Click::create([
            'article_id' => $article->id,
            'article_title' => $article->title,
        ]);
        return $next($request);
    }
}