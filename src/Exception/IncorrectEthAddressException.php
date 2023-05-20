<?php

namespace App\Exception;

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