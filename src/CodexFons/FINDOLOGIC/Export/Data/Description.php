<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Description extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('descriptions', 'description');
    }
}