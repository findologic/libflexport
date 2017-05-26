<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;

class AllKeywords extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allKeywords', 'keywords', ',');
    }
}