<?php

namespace App\QuoteSources;

interface IQuoteSource
{
    public function setup();
    public function getQuotesByAuthor(string $author, int $maxResults);
}
