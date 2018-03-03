<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareUrlValue;

class Url extends UsergroupAwareUrlValue
{
    public function __construct()
    {
        parent::__construct('urls', 'url');
    }
}
