<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class Description extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('descriptions', 'description');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'description';
    }
}
