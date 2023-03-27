<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class Summary extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('summaries', 'summary');
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'summary';
    }
}
