<?php

namespace App\Exception;

use DomainException;

class IncorrectEthAddressException extends DomainException
{
    /**
     * IncorrectEthAddressException constructor.
     */
    public function __construct()
    {
        parent::__construct('Incorrect address format', 400);
    }
}