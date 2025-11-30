<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendLeadNotificationEmail implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $to,
        public string $subject,
        public string $html
    ) {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Use the configured mailer (default `MAIL_MAILER` → smtp) for outgoing notifications.
            Mail::html($this->html, function ($message) {
                $message->to($this->to)->subject($this->subject);
            });

            Log::info('Email sent successfully', [
                'mailer' => config('mail.default'),
                'to' => $this->to,
                'subject' => $this->subject,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email', [
                'error' => $e->getMessage(),
                'mailer' => config('mail.default'),
                'to' => $this->to,
                'subject' => $this->subject
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Lead notification email job failed permanently', [
            'error' => $exception->getMessage(),
            'to' => $this->to,
            'subject' => $this->subject
        ]);
    }
}
