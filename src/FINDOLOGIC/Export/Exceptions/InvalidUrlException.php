<?php

namespace FINDOLOGIC\Export\Exceptions;

class InvalidUrlException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid url!');
    }
}
