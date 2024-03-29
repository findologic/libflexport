<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class EmptyElementsNotAllowedException extends RuntimeException
{
    public function __construct(string $elementType, string $elementKey)
    {
        parent::__construct(sprintf(
            'Elements with empty values are not allowed. "%s" with the name "%s"',
            $elementType,
            $elementKey
        ));
    }
}
