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
        parent::__construct('Verification server unavailable', 503);
    }
}