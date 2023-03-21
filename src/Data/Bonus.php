<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Bonus extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'bonus';
    }
}
