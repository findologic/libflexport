<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Summary extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('summaries', 'summary');
    }
}