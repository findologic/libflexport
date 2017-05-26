<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Bonus extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }
}