<?php

namespace App\Exceptions;

use Exception;

class CompetitionIncreaseScoreException extends GenericApiException
{
    public const HTTP_STATUS_CODE = '500';
    public const VERBOSE_STATUS_CODE = 'BAD_REQUEST';
}
