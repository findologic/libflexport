<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class BaseImageMissingException extends RuntimeException
{
    public function __construct()
    {
        $message = 'Base image without usergroup does\'t exist, exporting a “No Image Available" image is recommended!';
        parent::__construct($message);
    }
}
