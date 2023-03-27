<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

final class Ordernumber extends UsergroupAwareMultiValueItem
{
    public function __construct(string $value, string $usergroup = '')
    {
        parent::__construct('ordernumber', $value, $usergroup);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'ordernumber';
    }
}
