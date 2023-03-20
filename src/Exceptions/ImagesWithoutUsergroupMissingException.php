<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class ImagesWithoutUsergroupMissingException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('There exist no images without usergroup!');
    }
}
