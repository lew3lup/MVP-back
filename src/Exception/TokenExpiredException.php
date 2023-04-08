<?php

namespace App\Exception;

class TokenExpiredException extends UnauthorizedException
{
    /**
     * TokenExpiredException constructor.
     */
    public function __construct()
    {
        parent::__construct('TOKEN_EXPIRED');
    }
}