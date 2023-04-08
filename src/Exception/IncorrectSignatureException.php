<?php

namespace App\Exception;

use DomainException;

class IncorrectSignatureException extends BadRequestException
{
    /**
     * IncorrectSignatureException constructor.
     */
    public function __construct()
    {
        parent::__construct('INCORRECT_SIGNATURE');
    }
}