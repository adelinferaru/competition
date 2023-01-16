<?php

namespace App\Exceptions;

use Exception;

class PlayerAlreadyEnrolledException extends GenericApiException
{
    public const HTTP_STATUS_CODE = '404';
    public const VERBOSE_STATUS_CODE = 'BAD_REQUEST';
}
