<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/
use function Pest\Faker\faker;
uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

function prepareLocalQuotesSourceData () {
    config()->set('quotes.default_source', 'local_quotes');
    $quotesIntegration = config('quotes.default_source');
    $quotesSourceConcreteClass = config('quotes.sources.' . $quotesIntegration . '.class');
    $quotesMaxLimit = config('quotes.max_retrievable_quotes');


    $source = new $quotesSourceConcreteClass();
    $quotes = [];
    $fullName = null;
    for($i = 0; $i< 50; $i++) {
        if($i % 10 == 0) {
            $fullName = faker()->name;
        }
        $quotes [] = [
            'quote' => faker()->sentence,
            'author' => $fullName
        ];
    }
    $source->setQuotes($quotes);

    $correctAuthor = $quotes[0]['author'];
    $correctLimit = rand(1, $quotesMaxLimit);

    $incorrectAuthor = 'ZZZZ AAAA';
    $incorrectLimit = rand($quotesMaxLimit + 1, $quotesMaxLimit + 100);

    // Swap
    app()->instance(\App\QuoteSources\IQuoteSource::class, $source);

    return [
        'source' => $source,
        'correctAuthor' => $correctAuthor,
        'incorrectAuthor' => $incorrectAuthor,
        'correctLimit' => $correctLimit,
        'incorrectLimit' => $incorrectLimit
    ];
}
