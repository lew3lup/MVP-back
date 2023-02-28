<?php

namespace App\Exception;

use DomainException;

class UnauthorizedException extends DomainException
{
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Unauthorized', 401);
    }
}