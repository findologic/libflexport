<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;


class AllOrdernumbers extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allOrdernumbers', 'ordernumbers', '|');
    }
}