<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Url extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('urls', 'url');
    }
}