<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\Serializable;
use FINDOLOGIC\Export\Helpers\XMLHelper;

class Usergroup implements Serializable
{
    /** @var string */
    private $value;

    public function __construct($value)
    {
        $this->value = DataHelper::checkForEmptyValue($value);
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     * @inheritdoc
     */
    public function getDomSubtree(\DOMDocument $document)
    {
        $usergroupElem = XMLHelper::createElementWithText($document, 'usergroup', $this->getValue());

        return $usergroupElem;
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(array $availableProperties = [])
    {
        return $this->getValue();
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
