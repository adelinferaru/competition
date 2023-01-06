<?php

namespace App\Http\Controllers;

use App\Exceptions\QuotesApiError;
use App\Exceptions\QuotesApiException;
use App\Http\Requests\QuotesRequest;
use App\Services\QuotesService;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    protected QuotesService $quotesService;

    public function __construct(QuotesService $quotesService) {
        $this->quotesService = $quotesService;
    }

    public function shout(string $authorSlug, QuotesRequest $request): \Illuminate\Http\JsonResponse
    {
        $authorQuotes = $this->quotesService->getQuotesByAuthor($authorSlug, $request->validated('limit'));
        return response()->json($this->quotesService->shoutQuotes($authorQuotes), 200);
    }
}
