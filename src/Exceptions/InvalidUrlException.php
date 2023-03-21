<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class InvalidUrlException extends RuntimeException
{
    public function __construct(string $url)
    {
        parent::__construct(sprintf('"%s" is not a valid url!', $url));
    }
}
