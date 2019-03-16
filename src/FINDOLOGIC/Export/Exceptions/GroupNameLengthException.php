<?php

namespace FINDOLOGIC\Export\Exceptions;

class GroupNameLengthException extends InternalCharacterLimitException
{
    public function __construct($group, $characterLimit)
    {
        parent::__construct('Group with name', $group, $characterLimit);
    }
}