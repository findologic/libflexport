<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class InvalidUrlException extends RuntimeException
{
    public function __construct(string $url)
    {
        parent::__construct(sprintf('"%s" is not a valid url!', $url));
    }
}
