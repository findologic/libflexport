<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

final class GroupNameLengthException extends InternalCharacterLimitException
{
    public function __construct(string $group, int $characterLimit)
    {
        parent::__construct('Group with name', $group, $characterLimit);
    }
}
