<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class UsergroupsNotAllowedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Usergroups are not supported when using variants');
    }
}
