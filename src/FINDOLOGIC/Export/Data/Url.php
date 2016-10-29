<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Url extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('urls', 'url');
    }
}