<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

final class ItemIdLengthException extends InternalCharacterLimitException
{
    public function __construct(string $id, int $characterLimit)
    {
        parent::__construct('Item with id', $id, $characterLimit);
    }
}
