<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class exitCacheAuthorQuotesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $cacheKey;
    public array $quotes;
    public int $quoteCacheTimeLimit;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $cacheKey, array $quotes)
    {
        $this->cacheKey = $cacheKey;
        $this->quotes = $quotes;
        $this->quoteCacheTimeLimit = config('quotes.cache_time_limit');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::add($this->cacheKey, $this->quotes, $this->quoteCacheTimeLimit);
    }
}
