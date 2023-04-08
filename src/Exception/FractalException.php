<?php

namespace App\Exception;

use DomainException;

class FractalException extends DomainException
{
    /**
     * FractalException constructor.
     */
    public function __construct()
    {
        parent::__construct('VERIFICATION_SERVER_UNAVAILABLE', 503);
    }
}