<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class Url extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('urls', 'url');
    }

    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        foreach ($this->getValues() as $value) {
            DataHelper::validateUrl($value);
        }

        return parent::getDomSubtree($document);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'url';
    }
}
