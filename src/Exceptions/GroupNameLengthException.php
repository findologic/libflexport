<?php

namespace FINDOLOGIC\Export\Exceptions;

class GroupNameLengthException extends InternalCharacterLimitException
{
    public function __construct(string $group, string $characterLimit)
    {
        parent::__construct('Group with name', $group, $characterLimit);
    }
}
