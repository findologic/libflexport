<?php

namespace FINDOLOGIC\Export\Helpers;

use DOMDocument;
use DOMElement;
use FINDOLOGIC\Export\CSV\CSVConfig;

/**
 * Class UsergroupAwareMultiValueItem
 * @package FINDOLOGIC\Export\Helpers
 *
 * Single value for a UsergroupAwareMultiValue.
 *
 * When inheriting, make sure that the child class' constructor exposes $value and $usergroup, and calls the parent's
 * constructor with those values, plus the name of the XML element in which the value is wrapped.
 */
abstract class UsergroupAwareMultiValueItem implements Serializable, NameAwareValue
{
    private string $itemName;

    private string $value;

    private string $usergroup;

    public function __construct(string $itemName, mixed $value, ?string $usergroup)
    {
        $this->value = DataHelper::checkForEmptyValue($this->getValueName(), $value);
        $this->itemName = $itemName;
        $this->usergroup = $usergroup;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getUsergroup(): string
    {
        return $this->usergroup;
    }

    /**
     * @inheritdoc
     */
    public function getDomSubtree(DOMDocument $document): DOMElement
    {
        return XMLHelper::createElementWithText($document, $this->itemName, $this->getValue());
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        return $this->getValue();
    }
}
