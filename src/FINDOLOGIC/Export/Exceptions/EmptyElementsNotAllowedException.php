<?php

namespace FINDOLOGIC\Export\Exceptions;

class EmptyElementsNotAllowedException extends \RuntimeException
{
    public function __construct(string $elementType, string $elementKey)
    {
        parent::__construct(
            sprintf(
                'Elements with empty values are not allowed. "%s" with the name "%s"',
                $elementType,
                $elementKey
            )
        );
    }
}
