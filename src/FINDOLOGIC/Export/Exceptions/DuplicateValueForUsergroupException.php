<?php

namespace FINDOLOGIC\Export\Exceptions;

class DuplicateValueForUsergroupException extends \RuntimeException
{
    public function __construct(string $key, ?string $usergroup)
    {
        parent::__construct(sprintf('Property "%s" already has a value for usergroup "%s".', $key, $usergroup));
    }
}
