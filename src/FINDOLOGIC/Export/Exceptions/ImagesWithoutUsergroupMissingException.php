<?php

namespace FINDOLOGIC\Export\Exceptions;

class ImagesWithoutUsergroupMissingException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('There exist no images without usergroup!');
    }
}
