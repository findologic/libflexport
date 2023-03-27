<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

final class OverriddenPrice extends UsergroupAwareNumericValue
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
