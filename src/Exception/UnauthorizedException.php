<?php

namespace App\Exception;

use DomainException;

class UnauthorizedException extends DomainException
{
    /**
     * UnauthorizedException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'UNAUTHORIZED', 401);
    }
}