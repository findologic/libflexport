<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Bonus extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }
}
