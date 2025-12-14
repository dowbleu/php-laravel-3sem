<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Comment;
use App\Models\Click;
use App\Models\User;
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
    protected $description = 'Send daily statistics to moderators';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Получаем количество просмотров статей за сегодня
        $articleViews = Click::whereDate('created_at', Carbon::today())->count();

        // Получаем количество новых комментариев за сегодня
        $commentsCount = Comment::whereDate('created_at', Carbon::today())->count();

        // Получаем всех модераторов
        $moderators = User::where('role', 'moderator')->get();

        if ($moderators->isEmpty()) {
            $this->error('No moderators found!');
            return;
        }

        // Отправляем статистику каждому модератору
        foreach ($moderators as $moderator) {
            Mail::to($moderator->email)->send(new StatMail($articleViews, $commentsCount));
        }

        $this->info('Statistics sent to ' . $moderators->count() . ' moderator(s)');
    }
}