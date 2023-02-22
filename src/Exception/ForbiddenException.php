<?php

namespace App\Exception;

use DomainException;

class ForbiddenException extends DomainException
{
    /**
     * ForbiddenException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Forbidden', 403);
    }
}