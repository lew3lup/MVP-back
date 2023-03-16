<?php

namespace App\Exception;

use DomainException;

class RequestDataException extends DomainException
{
    /**
     * RequestDataException constructor.
     */
    public function __construct()
    {
        parent::__construct('Please fill in all required fields', 400);
    }
}
