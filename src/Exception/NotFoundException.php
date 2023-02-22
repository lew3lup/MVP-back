<?php

namespace App\Exception;

use DomainException;

class NotFoundException extends DomainException
{
    /**
     * NotFoundException constructor.
     */
    public function __construct()
    {
        parent::__construct('Not found', 404);
    }
}