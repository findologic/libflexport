<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ImagesWithoutUsergroupMissingException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('There exist no images without usergroup!');
    }
}
