<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Bonus extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }
}
