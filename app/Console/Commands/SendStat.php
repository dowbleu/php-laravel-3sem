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

        $this->info("Article views today: {$articleViews}");
        $this->info("Comments today: {$commentsCount}");

        // Получаем адрес для отправки статистики (из env или используем MAIL_FROM_ADDRESS)
        $statisticsEmail = env('MAIL_STATISTICS_TO', config('mail.from.address'));

        if (empty($statisticsEmail)) {
            $this->error('No email address configured for statistics!');
            $this->warn('Set MAIL_STATISTICS_TO in .env or configure MAIL_FROM_ADDRESS');
            return;
        }

        $this->info("Sending statistics to: {$statisticsEmail}");

        try {
            Mail::to($statisticsEmail)->send(new StatMail($articleViews, $commentsCount));

            $this->info("✓ Email sent successfully to {$statisticsEmail}");

            Log::info("Statistics email sent to {$statisticsEmail}", [
                'article_views' => $articleViews,
                'comments_count' => $commentsCount
            ]);
        } catch (\Exception $e) {
            $this->error("✗ Failed to send email to {$statisticsEmail}: " . $e->getMessage());
            $this->error("  Error details: " . $e->getFile() . ':' . $e->getLine());
            Log::error("Failed to send statistics email to {$statisticsEmail}", [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}