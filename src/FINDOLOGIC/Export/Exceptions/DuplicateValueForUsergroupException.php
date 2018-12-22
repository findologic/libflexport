<?php

namespace FINDOLOGIC\Export\Exceptions;

class DuplicateValueForUsergroupException extends \RuntimeException
{
    public function __construct($key, $usergroup)
    {
        parent::__construct(sprintf('Property "%s" already has a value for usergroup "%s".', $key, $usergroup));
    }
}
