<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class GenericException extends Exception 
{
    public function __construct(
        $message,
        $code = 0,
        public ?int $httpCode = null,
        public ?string $internalCode = null,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}