<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Url extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('urls', 'url');
    }

    public function getDomSubtree(\DOMDocument $document)
    {
        foreach ($this->getValues() as $value) {
            DataHelper::validateUrl($value);
        }

        return parent::getDomSubtree($document);
    }
}
