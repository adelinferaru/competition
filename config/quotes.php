<?php
return [
    'default_source' => 'local_quotes',

    'sources' => [
        'local_quotes' => [
            'class' => App\QuoteSources\LocalQuotes::class,
            'path' => env('LOCAL_QUOTES_FILE_PATH', 'local_quotes/quotes.json')
        ]
    ],

    'cache_time_limit' => 10 * 60,
    'max_retrievable_quotes' => env('MAX_RETRIEVABLE_QUOTES', 10)
];
