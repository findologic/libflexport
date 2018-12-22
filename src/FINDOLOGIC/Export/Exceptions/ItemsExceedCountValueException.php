<?php

namespace FINDOLOGIC\Export\Exceptions;

class ItemsExceedCountValueException extends \RuntimeException
{
    public function __construct()
    {
        $message = 'The number of items must not exceed the count value';
        parent::__construct($message);
    }
}
