<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

final class Price extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('prices', 'price');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'price';
    }
}
