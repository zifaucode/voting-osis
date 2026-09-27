<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BroadcastVoteUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Jumlah percobaan ulang jika gagal.
     */
    public int $tries = 2;

    /**
     * Timeout job dalam detik.
     */
    public int $timeout = 5;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \App\Services\RealtimeBroadcaster::broadcast();
    }
}
