<?php

namespace App\Exceptions;

use Exception;

abstract class GenericApiException extends Exception
{
    public const HTTP_STATUS_CODE = '';
    public const VERBOSE_STATUS_CODE = '';

    public static function getHttpStatusCode(): string
    {
        return static::HTTP_STATUS_CODE;
    }

    public static function getVerboseStatusCode(): string
    {
        return static::VERBOSE_STATUS_CODE;
    }
}
