<?php

namespace FINDOLOGIC\Export\Data;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Usergroup implements Serializable, NameAwareValue
{
    /** @var string */
    private $value;

    public function __construct($value)
    {
        $this->value = DataHelper::checkForEmptyValue($this->getValueName(), $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        return XMLHelper::createElementWithText($document, 'usergroup', $this->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(array $availableProperties = []): string
    {
        return $this->getValue();
    }

    public function __toString(): string
    {
        return $this->getValue();
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'usergroup';
    }
}
