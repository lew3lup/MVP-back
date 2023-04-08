<?php

namespace App\Exception;

use DomainException;

class BadRequestException extends DomainException
{
    /**
     * RequestDataException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'BAD_REQUEST', 400);
    }
}
