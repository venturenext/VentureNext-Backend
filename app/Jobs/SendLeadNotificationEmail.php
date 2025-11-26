<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

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
        $apiKey = config('services.resend.api_key');

        // Fallback to SMTP if no Resend API key
        if (!$apiKey) {
            $this->sendViaMailer();
            return;
        }

        try {
            // Use Resend HTTP API (faster, more reliable than SMTP)
            $response = Http::timeout(10)
                ->withToken($apiKey)
                ->post('https://api.resend.com/emails', [
                    'from' => config('mail.from.address'),
                    'to' => [$this->to],
                    'subject' => $this->subject,
                    'html' => $this->html,
                ]);

            if (!$response->successful()) {
                throw new \Exception('Resend API error: ' . $response->body());
            }

            Log::info('Email sent successfully via Resend API', [
                'to' => $this->to,
                'subject' => $this->subject,
                'response' => $response->json()
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send lead notification email', [
                'error' => $e->getMessage(),
                'to' => $this->to,
                'subject' => $this->subject
            ]);
            throw $e;
        }
    }

    /**
     * Fallback to Laravel Mailer (SMTP)
     */
    private function sendViaMailer(): void
    {
        try {
            \Illuminate\Support\Facades\Mail::html($this->html, function ($message) {
                $message->to($this->to)->subject($this->subject);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send email via Mailer', [
                'error' => $e->getMessage(),
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
