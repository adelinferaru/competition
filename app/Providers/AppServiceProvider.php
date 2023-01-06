<?php

namespace App\Providers;

use App\QuoteSources\IQuoteSource;
use App\QuoteSources\LocalQuotes;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $defaultQuotesSource = config('quotes.default_source');
        $quotesSourceConcreteClass = config('quotes.sources.' . $defaultQuotesSource . '.class');
        $this->app->bind('App\QuoteSources\IQuoteSource', $quotesSourceConcreteClass);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
