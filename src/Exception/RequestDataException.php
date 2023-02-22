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
        $this->message = 'Please fill in all required fields';
    }
}
