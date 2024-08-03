<?php

namespace App\Console\Commands;

use App\Mail\CronJobMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:testmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify the cron job';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::info('Every minuite cron job testing');
        Mail::to('kunsang.dotlinkertech@gmail.com')->send(new CronJobMail());
        $this->info('Test email sent successfully.');
        return 0;
    }
}
