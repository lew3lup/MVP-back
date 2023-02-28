<?php

namespace App\Exception;

use DomainException;

class EntityNotFoundException extends DomainException
{
    /**
     * EntityNotFoundException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'Not found', 404);
    }
}
