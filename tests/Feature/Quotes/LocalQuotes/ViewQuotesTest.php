<?php

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

uses(WithFaker::class);

$title = PHP_EOL .  'TESTING LOCAL_QUOTES SOURCE' . PHP_EOL;
$title .=           '***************************' . PHP_EOL;
fwrite(STDERR, print_r($title, TRUE));

beforeEach(function () {
    //
});

it('returns 200 status code when calling shout endpoint with correct params', function () {
    $sourceData = prepareLocalQuotesSourceData();
    $correctEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['correctAuthor'], '-'),
        'limit' => $sourceData['correctLimit']]);
    // var_dump($correctEndpoint);
    $this->get($correctEndpoint)->assertStatus(200);
});

it('returns 400 status code when calling shout endpoint with correct author but wrong limit', function () {
    $sourceData = prepareLocalQuotesSourceData();
    $correctAuthorIncorrectLimitEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['correctAuthor'], '-'),
        'limit' => $sourceData['incorrectLimit']]);
    // var_dump($correctAuthorIncorrectLimitEndpoint);
    $this->get($correctAuthorIncorrectLimitEndpoint)->assertStatus(400);
});

it('returns 404 status code when calling shout endpoint with incorrect author', function () {
    $sourceData = prepareLocalQuotesSourceData();
    $incorrectAuthorCorrectLimitEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['incorrectAuthor'], '-'),
        'limit' => $sourceData['correctLimit']]);
    // var_dump($incorrectAuthorCorrectLimitEndpoint);
    $this->get($incorrectAuthorCorrectLimitEndpoint)->assertStatus(404);
});

it('returns 400 status code when calling shout endpoint with incorrect params (author and limit)', function () {
    $sourceData = prepareLocalQuotesSourceData();
    $incorrectAuthorAndLimitEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['incorrectAuthor'], '-'),
        'limit' => $sourceData['incorrectLimit']]);
    // var_dump($incorrectAuthorAndLimitEndpoint);
    $this->get($incorrectAuthorAndLimitEndpoint)->assertStatus(400);
});

it('returns shouted quotes (all uppercase letters and ending with !)', function() {
    $sourceData = prepareLocalQuotesSourceData();
    $quotes = $sourceData['source']->getQuotes();
    $firstQuote = $quotes[0]['quote'];
    $correctEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['correctAuthor'], '-'),
        'limit' => $sourceData['correctLimit']]);
    // var_dump($correctEndpoint);

    $response = $this->get($correctEndpoint);
    var_dump(count($response->json()));

    $this->assertContains(
        preg_replace('/[^A-Z0-9]+$/i', '', trim(strtoupper($firstQuote))) . '!',
        $response->json()
    );
});

it('returns exacly {limit} quotes when available', function() {
    $sourceData = prepareLocalQuotesSourceData();
    $customMaxLimit = 2;
    $correctEndpoint = route('quotes.shout', [
        'authorSlug' => Str::slug($sourceData['correctAuthor'], '-'),
        'limit' => $customMaxLimit]);
    // var_dump($correctEndpoint);

    $shouted = $this->get($correctEndpoint)->json();

    $this->expect(count($shouted))->toBe($customMaxLimit);
});


/*$response = \Illuminate\Support\Facades\Http::get('/shout/steve-jobs?limit=2');

    it('contains a known shout from Steve Jobs')
    ->assertContains(
        "YOUR TIME IS LIMITED, SO DON'T WASTE IT LIVING SOMEONE ELSE'S LIFE!",
        $response->json(),
        'xxxx'
    );*/
