<?php

namespace App\Services;


use App\Exceptions\AuthorNotFoundException;
use App\Jobs\CacheAuthorQuotesJob;
use App\QuoteSources\IQuoteSource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class QuotesService
{
    protected IQuoteSource $quoteSource;
    protected int $quoteCacheTimeLimit;
    protected int $maxRetrievableQuotes;

    public function __construct(IQuoteSource $quoteSource) {
        $this->quoteSource = $quoteSource;
        $this->quoteCacheTimeLimit = config('quotes.cache_time_limit');
        $this->maxRetrievableQuotes = config('quotes.max_retrievable_quotes');
    }

    /**
     * @throws AuthorNotFoundException
     */
    public function getQuotesByAuthor(string $authorSlug, int $limit) :array
    {
        $cacheKey = 'author:' . $authorSlug;
        if (Cache::has($cacheKey)) {
            $quotesByAuthor = Cache::get($cacheKey);
        } else {
           $quotesByAuthor = $this->quoteSource->getQuotesByAuthor($authorSlug, $this->maxRetrievableQuotes);

            if(count($quotesByAuthor)) {
                CacheAuthorQuotesJob::dispatch($cacheKey, $quotesByAuthor);
            }
        }

        if(count($quotesByAuthor) == 0) {
            // var_dump($authorSlug, $this->quoteSource->getQuotes()[0]);
            throw (new AuthorNotFoundException('There are no quotes by ' . $this->normalizeAuthorName($authorSlug) , 404));
        }

        return array_slice($quotesByAuthor, 0, $limit);
    }

    public function shoutQuotes(array $quotes): array
    {
        $shoutedQuotes = [];
        foreach ($quotes as $quote) {
            $shoutedQuotes [] = preg_replace('/[^A-Z0-9]+$/i', '', trim(strtoupper($quote))) . '!';
        }
        return $shoutedQuotes;
    }

    public function normalizeAuthorName(string $author) :string
    {
        return Str::headline(str_replace('-', ' ', $author));
    }
}
