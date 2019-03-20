<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class BaseImageMissingException extends RuntimeException
{
    public function __construct()
    {
        $message = 'Base image without usergroup does\'t exist, exporting a “No Image Available" image is recommended!';
        parent::__construct($message);
    }
}
