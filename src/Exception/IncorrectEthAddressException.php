<?php

namespace App\Exception;

use DomainException;

class IncorrectEthAddressException extends BadRequestException
{
    /**
     * IncorrectEthAddressException constructor.
     */
    public function __construct()
    {
        parent::__construct('INCORRECT_ADDRESS_FORMAT');
    }
}