<?php

namespace App\Jobs\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPasswordResetLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email, $url;

    /**
     * Create a new job instance.
     */
    public function __construct(string $email, string $url)
    {
        $this->url = $url;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
