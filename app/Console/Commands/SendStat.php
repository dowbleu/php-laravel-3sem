<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\Click;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\StatMail;

class SendStat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-stat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $article_counts = Click::groupBy('article_id')->get()->map(function ($item) {
            return ['article_id' => $item->article_id, 'count' => $item->count()];
        });
        Log::alert($article_counts);
        $comments = Comment::whereDate('created_at', Carbon::today())->count();
        Mail::to('moosbeere_O@mail.ru')->send(new StatMail($comments));
    }
}