<?php

namespace App\Exception;

class AlreadyVerifiedException extends ConflictException
{
    /**
     * AlreadyVerifiedException constructor.
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?? 'ALREADY_VERIFIED');
    }
}