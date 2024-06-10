<?php

namespace App\Exceptions;

use Throwable;

class MovieNotFoundException extends GenericException 
{
    public function __construct(
        $message = 'This movie does not exist.',
        $code = 0,
        ?int $httpCode = 404,
        ?string $internalCode = 'STEEL404',
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $httpCode, $internalCode, $previous);
    }
}