<?php

namespace FINDOLOGIC\Export\Exceptions;

class ItemIdLengthException extends InternalCharacterLimitException
{
    public function __construct(string $id, string $characterLimit)
    {
        parent::__construct('Item with id', $id, $characterLimit);
    }
}
