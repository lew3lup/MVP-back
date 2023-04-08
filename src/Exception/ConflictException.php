<?php

namespace App\Exception;

use DomainException;

class ConflictException extends DomainException
{
    /**
     * ConflictException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'CONFLICT', 409);
    }
}