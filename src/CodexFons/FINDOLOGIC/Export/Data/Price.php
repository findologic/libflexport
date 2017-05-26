<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Price extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('prices', 'price');
    }
}