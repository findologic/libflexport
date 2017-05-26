<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Summary extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('summaries', 'summary');
    }
}