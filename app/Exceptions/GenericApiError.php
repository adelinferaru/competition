<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use ReflectionClass;
use stdClass;

class GenericApiError
{
    private \Throwable $e;
    private string $httpStatusCode;
    private string $verboseStatusCode;
    protected $message;

    public function __construct(\Throwable $e, string $message = null) {
        $this->e = $e;
        $this->httpStatusCode = $this->e->getCode();
        $this->verboseStatusCode = 'EXCEPTION';
        $this->message = $message ?? $this->e->getMessage();

        $this->create();

        return $this;
    }

    private function create () {
        $reflector = new ReflectionClass(get_class($this->e));

        if($reflector->hasMethod('getHttpStatusCode')) {
            /* @var $exception GenericApiException */
            $exception = $this->e;
            $this->httpStatusCode = $exception->getHttpStatusCode();
            $this->verboseStatusCode = $exception->getVerboseStatusCode();
        } elseif ($this->e instanceof ValidationException) {
            $this->httpStatusCode = '400';
            $this->verboseStatusCode = 'INVALID_PARAMETERS';
        } else {
            $this->httpStatusCode = '500';
            $this->verboseStatusCode = 'GENERIC_ERROR';
        }
    }

    public function toArray() :array
    {
        return [
            'hCode' => $this->httpStatusCode,
            'vCode' => $this->verboseStatusCode,
            'message' => $this->message
        ];
    }

    public function toObject() : stdClass
    {
        return json_decode(json_encode($this->toArray()));
    }
}
