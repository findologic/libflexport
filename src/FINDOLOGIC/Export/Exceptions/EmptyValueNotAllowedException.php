<?php

namespace FINDOLOGIC\Export\Exceptions;

class EmptyValueNotAllowedException extends \RuntimeException
{
    public function __construct(string $message = 'Empty values are not allowed!')
    {
        parent::__construct($message);
    }
}
