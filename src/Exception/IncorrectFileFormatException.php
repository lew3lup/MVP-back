<?php

namespace App\Exception;

class IncorrectFileFormatException extends BadRequestException
{
    /**
     * IncorrectFileFormatException constructor.
     */
    public function __construct()
    {
        parent::__construct('INCORRECT_FILE_FORMAT');
    }
}