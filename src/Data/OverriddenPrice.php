<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class OverriddenPrice extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('overriddenPrices', 'overriddenPrice');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'overriddenPrice';
    }
}
