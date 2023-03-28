<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class Name extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('names', 'name');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'name';
    }
}
