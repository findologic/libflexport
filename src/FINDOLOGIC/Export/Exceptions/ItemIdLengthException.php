<?php

namespace FINDOLOGIC\Export\Exceptions;

class ItemIdLengthException extends InternalCharacterLimitException
{
    public function __construct($id, $characterLimit)
    {
        parent::__construct('Item with id', $id, $characterLimit);
    }
}