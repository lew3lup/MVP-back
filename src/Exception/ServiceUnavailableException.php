<?php

namespace App\Exception;

use DomainException;

class ServiceUnavailableException extends DomainException
{
    /**
     * ServiceUnavailableException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'SERVICE_UNAVAILABLE', 503);
    }
}