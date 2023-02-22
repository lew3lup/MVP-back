<?php

namespace App\Exception;

use DomainException;

class IncorrectSignatureException extends DomainException
{
    /**
     * IncorrectSignatureException constructor.
     */
    public function __construct()
    {
        parent::__construct('Incorrect user signature', 400);
    }
}