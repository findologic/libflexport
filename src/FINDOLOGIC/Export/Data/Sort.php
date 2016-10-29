<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }
}