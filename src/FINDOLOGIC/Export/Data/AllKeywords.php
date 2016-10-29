<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValue;

class AllKeywords extends UsergroupAwareMultiValue
{
    public function __construct()
    {
        parent::__construct('allKeywords', 'keywords', ',');
    }
}