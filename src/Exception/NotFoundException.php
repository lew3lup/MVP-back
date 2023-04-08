<?php

namespace App\Exception;

use DomainException;

class NotFoundException extends DomainException
{
    /**
     * NotFoundException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'NOT_FOUND', 404);
    }
}