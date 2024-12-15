<?php

namespace App\Jobs\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEmailVerificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;
    private $url;
    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $url)
    {
        $this->email = $email;
        $this->url = $url;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
