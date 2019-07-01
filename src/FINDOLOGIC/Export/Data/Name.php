<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Name extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('names', 'name');
    }

    public function getValueName(): string
    {
        return 'name';
    }
}
