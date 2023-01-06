<?php

namespace App\QuoteSources;

use App\QuoteSources\IQuoteSource;
use Illuminate\Support\Str;

class LocalQuotes implements IQuoteSource
{
    protected $localQuotesJsonFilePath;
    protected $quotes = null;

    public function __construct() {
        $this->setup();
    }

    public function setup()
    {
        $this->localQuotesJsonFilePath = config('quotes.sources.local_quotes.path');
    }

    public function setQuotes(array $quotes) {
        $this->quotes = $quotes;
    }

    public function getQuotes() {
        return $this->quotes;
    }

    public function getQuotesFromJsonFile () {
        if (file_exists(storage_path($this->localQuotesJsonFilePath))) {
            $json = file_get_contents(storage_path($this->localQuotesJsonFilePath));

            $json = $this->cleanText($json);

            // Get an array of quotes
            $this->setQuotes(json_decode($json, true)['quotes']);
        }
        else {
            return response()->json(['errors' => ['Invalid source file.']], 500);
        }
    }

    protected function cleanText($text): string
    {
        $encoding = mb_detect_encoding($text, mb_list_encodings());
        if ('UTF-8' != $encoding) {
            $text = mb_convert_encoding($text, 'UTF-8', $encoding);
        }

        // Fix apostrophe
        // $json = str_replace('’', "'", $json);
        // $json = str_replace("\u{2019}", "\u{0027}", $json);
        $text = preg_replace('/\x{2019}/u', "\u{0027}", $text);

        return $text;
    }

    public function getQuotesByAuthor(string $authorSlug, int $maxResults)
    {
        $authorQuotes = [];
        $countFound = 0;

        if (!$this->quotes) {
            $this->getQuotesFromJsonFile();
        }

        foreach ($this->quotes as $quote) {
            if (Str::slug($quote['author']) == $authorSlug) {
                $authorQuotes [] = $quote['quote'];
                $countFound ++;
            }
            if ($countFound == $maxResults) {
                break;
            }
        }
        return $authorQuotes;
    }
}
