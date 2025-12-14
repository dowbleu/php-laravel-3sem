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
        // Извлекаем ID статьи из URL
        preg_match('/\d+/', $request->path(), $matches);

        if (!empty($matches) && isset($matches[0])) {
            $articleId = $matches[0];
            $article = Article::find($articleId);

            // Сохраняем просмотр только если статья существует
            if ($article) {
                Click::create([
                    'article_id' => $article->id,
                    'article_title' => $article->title,
                ]);
            }
        }

        return $next($request);
    }
}